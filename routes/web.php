<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth routes
Auth::routes(['register' => false, 'reset' => false]);

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');

    // Vehicles - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers', DriverController::class);
        Route::resource('users', UserController::class);
        Route::resource('locations', \App\Http\Controllers\LocationController::class);
    });

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Activity Logs - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/activity-logs', function () {
            $logs = \Spatie\Activitylog\Models\Activity::with('causer')
                ->latest()
                ->paginate(30);
            return view('activity-logs.index', compact('logs'));
        })->name('activity-logs.index');
    });
});
