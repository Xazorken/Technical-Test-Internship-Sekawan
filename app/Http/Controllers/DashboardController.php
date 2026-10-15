<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Stats cards
        $stats = [
            'total_bookings'  => Booking::count(),
            'pending'         => Booking::where('status', 'pending')->count(),
            'approved'        => Booking::where('status', 'approved')->count(),
            'in_progress'     => Booking::where('status', 'in_progress')->count(),
            'completed'       => Booking::where('status', 'completed')->count(),
            'vehicles'        => Vehicle::where('status', 'available')->count(),
        ];

        // For approver: count pending approvals for them
        if ($user->hasRole(['approver_l1', 'approver_l2'])) {
            $level = $user->hasRole('approver_l1') ? 1 : 2;
            $stats['my_pending'] = \App\Models\BookingApproval::where('approver_id', $user->id)
                ->where('level', $level)
                ->where('status', 'pending')
                ->count();
        }

        // Monthly booking chart data (last 6 months)
        $monthlyData = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $chartLabels = [];
        $chartData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $date          = now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');
            $found         = $monthlyData->first(fn($d) => $d->month == $date->month && $d->year == $date->year);
            $chartData[]   = $found ? $found->total : 0;
        }

        // Vehicle usage by type chart
        $vehicleUsage = Booking::select('vehicles.type', DB::raw('COUNT(*) as total'))
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->whereIn('bookings.status', ['approved', 'in_progress', 'completed'])
            ->groupBy('vehicles.type')
            ->get();

        // Status distribution
        $statusData = Booking::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'vehicle', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        activity()->log('Mengakses dashboard');

        return view('dashboard', compact(
            'stats', 'chartLabels', 'chartData',
            'vehicleUsage', 'statusData', 'recentBookings'
        ));
    }
}
