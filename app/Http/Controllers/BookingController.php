<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $query    = Booking::with(['user', 'vehicle', 'driver', 'approvals']);

        // Approver only sees bookings they need to approve or already handled
        if ($user->hasRole('approver_l1')) {
            $query->whereHas('approvals', fn($q) => $q->where('approver_id', $user->id)->where('level', 1));
        } elseif ($user->hasRole('approver_l2')) {
            $query->whereHas('approvals', fn($q) => $q->where('approver_id', $user->id)->where('level', 2));
        }

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('start_datetime', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('start_datetime', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        activity()->log('Melihat daftar pemesanan kendaraan');

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $this->authorize('create-booking');

        $vehicles  = Vehicle::where('status', 'available')->with('location')->get();
        $drivers   = Driver::where('status', 'available')->get();
        $locations = Location::where('is_active', true)->get();
        // Approvers: Level 1 = approver_l1, Level 2 = approver_l2
        $approversL1 = User::role('approver_l1')->get();
        $approversL2 = User::role('approver_l2')->get();

        return view('bookings.create', compact('vehicles', 'drivers', 'locations', 'approversL1', 'approversL2'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-booking');

        $validated = $request->validate([
            'vehicle_id'       => 'required|exists:vehicles,id',
            'driver_id'        => 'nullable|exists:drivers,id',
            'from_location_id' => 'nullable|exists:locations,id',
            'destination'      => 'required|string|max:255',
            'purpose'          => 'required|string',
            'start_datetime'   => 'required|date|after:now',
            'end_datetime'     => 'required|date|after:start_datetime',
            'passenger_count'  => 'required|integer|min:1',
            'approver_l1_id'   => 'required|exists:users,id',
            'approver_l2_id'   => 'required|exists:users,id',
            'notes'            => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                ...$validated,
                'user_id'        => auth()->id(),
                'booking_number' => Booking::generateBookingNumber(),
                'status'         => 'pending',
            ]);

            // Create approval records for 2 levels
            BookingApproval::create([
                'booking_id'  => $booking->id,
                'approver_id' => $validated['approver_l1_id'],
                'level'       => 1,
                'status'      => 'pending',
            ]);

            BookingApproval::create([
                'booking_id'  => $booking->id,
                'approver_id' => $validated['approver_l2_id'],
                'level'       => 2,
                'status'      => 'pending',
            ]);

            // Mark vehicle as in-use pending approval
            // Vehicle::find($validated['vehicle_id'])->update(['status' => 'in_use']);

            DB::commit();

            activity()
                ->performedOn($booking)
                ->log("Membuat pemesanan kendaraan #{$booking->booking_number}");

            return redirect()->route('bookings.show', $booking)
                ->with('success', "Pemesanan {$booking->booking_number} berhasil dibuat dan menunggu persetujuan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat pemesanan: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'vehicle.location', 'driver', 'fromLocation', 'approvals.approver']);

        activity()->performedOn($booking)->log("Melihat detail pemesanan #{$booking->booking_number}");

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        if (!in_array($booking->status, ['pending'])) {
            return back()->with('error', 'Pemesanan yang sudah diproses tidak dapat diubah.');
        }

        $vehicles    = Vehicle::where('status', 'available')->orWhere('id', $booking->vehicle_id)->with('location')->get();
        $drivers     = Driver::where('status', 'available')->orWhere('id', $booking->driver_id)->get();
        $locations   = Location::where('is_active', true)->get();
        $approversL1 = User::role('approver_l1')->get();
        $approversL2 = User::role('approver_l2')->get();

        return view('bookings.edit', compact('booking', 'vehicles', 'drivers', 'locations', 'approversL1', 'approversL2'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        if (!in_array($booking->status, ['pending'])) {
            return back()->with('error', 'Pemesanan yang sudah diproses tidak dapat diubah.');
        }

        $validated = $request->validate([
            'vehicle_id'       => 'required|exists:vehicles,id',
            'driver_id'        => 'nullable|exists:drivers,id',
            'from_location_id' => 'nullable|exists:locations,id',
            'destination'      => 'required|string|max:255',
            'purpose'          => 'required|string',
            'start_datetime'   => 'required|date',
            'end_datetime'     => 'required|date|after:start_datetime',
            'passenger_count'  => 'required|integer|min:1',
            'notes'            => 'nullable|string',
        ]);

        $booking->update($validated);

        activity()->performedOn($booking)->log("Mengubah pemesanan #{$booking->booking_number}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if (!in_array($booking->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Pemesanan tidak dapat dihapus.');
        }

        activity()->performedOn($booking)->log("Menghapus pemesanan #{$booking->booking_number}");

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Pemesanan berhasil dihapus.');
    }

    public function approve(Request $request, Booking $booking)
    {
        $user     = auth()->user();
        $level    = $user->hasRole('approver_l1') ? 1 : 2;

        $approval = BookingApproval::where('booking_id', $booking->id)
            ->where('approver_id', $user->id)
            ->where('level', $level)
            ->where('status', 'pending')
            ->firstOrFail();

        $validated = $request->validate([
            'action' => 'required|in:approved,rejected',
            'notes'  => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $approval->update([
                'status'   => $validated['action'],
                'notes'    => $validated['notes'],
                'acted_at' => now(),
            ]);

            if ($validated['action'] === 'approved') {
                if ($level === 1) {
                    // Check if level 2 exists
                    $l2 = BookingApproval::where('booking_id', $booking->id)->where('level', 2)->first();
                    if ($l2) {
                        $booking->update(['status' => 'approved_l1']);
                        activity()->performedOn($booking)->log("Pemesanan #{$booking->booking_number} disetujui Level 1");
                    } else {
                        $booking->update(['status' => 'approved']);
                    }
                } else {
                    // Level 2 approved = fully approved
                    $booking->update(['status' => 'approved']);
                    // Update vehicle & driver status
                    $booking->vehicle->update(['status' => 'in_use']);
                    if ($booking->driver) {
                        $booking->driver->update(['status' => 'on_duty']);
                    }
                    activity()->performedOn($booking)->log("Pemesanan #{$booking->booking_number} disetujui Level 2 - APPROVED");
                }
            } else {
                // Rejected
                $booking->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $validated['notes'],
                ]);
                activity()->performedOn($booking)->log("Pemesanan #{$booking->booking_number} DITOLAK Level {$level}");
            }

            DB::commit();

            $msg = $validated['action'] === 'approved' ? 'Pemesanan berhasil disetujui.' : 'Pemesanan berhasil ditolak.';
            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    public function complete(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'odometer_end' => 'nullable|integer',
            'fuel_used'    => 'nullable|numeric',
            'notes'        => 'nullable|string',
        ]);

        $booking->update([
            ...$validated,
            'status' => 'completed',
        ]);

        // Free up vehicle and driver
        $booking->vehicle->update(['status' => 'available']);
        if ($booking->driver) {
            $booking->driver->update(['status' => 'available']);
        }

        activity()->performedOn($booking)->log("Pemesanan #{$booking->booking_number} diselesaikan");

        return back()->with('success', 'Pemesanan berhasil diselesaikan.');
    }
}
