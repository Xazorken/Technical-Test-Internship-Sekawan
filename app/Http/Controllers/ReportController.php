<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'vehicle', 'driver', 'approvals']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('start_datetime', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('start_datetime', '<=', $request->date_to);
        }
        if ($request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        $bookings = $query->latest()->paginate(20)->withQueryString();

        $vehicles = \App\Models\Vehicle::orderBy('name')->get();

        activity()->log('Mengakses halaman laporan pemesanan');

        return view('reports.index', compact('bookings', 'vehicles'));
    }

    public function export(Request $request)
    {
        $filename = 'laporan-pemesanan-' . now()->format('Y-m-d') . '.xlsx';

        activity()->log("Export laporan pemesanan kendaraan ke Excel: {$filename}");

        return Excel::download(new BookingsExport($request->all()), $filename);
    }
}
