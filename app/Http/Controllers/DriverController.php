<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Location;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('location')->latest()->paginate(15);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        return view('drivers.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'license_number'      => 'required|string|unique:drivers',
            'license_type'        => 'required|in:A,B1,B2,C',
            'license_expiry_date' => 'nullable|date',
            'phone'               => 'nullable|string|max:20',
            'address'             => 'nullable|string',
            'location_id'         => 'nullable|exists:locations,id',
            'status'              => 'required|in:available,on_duty,off,inactive',
            'is_active'           => 'boolean',
        ]);

        $driver = Driver::create($validated);
        activity()->performedOn($driver)->log("Driver {$driver->name} ditambahkan");

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    public function edit(Driver $driver)
    {
        $locations = Location::where('is_active', true)->get();
        return view('drivers.edit', compact('driver', 'locations'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'license_number'      => 'required|string|unique:drivers,license_number,' . $driver->id,
            'license_type'        => 'required|in:A,B1,B2,C',
            'license_expiry_date' => 'nullable|date',
            'phone'               => 'nullable|string|max:20',
            'address'             => 'nullable|string',
            'location_id'         => 'nullable|exists:locations,id',
            'status'              => 'required|in:available,on_duty,off,inactive',
        ]);

        $driver->update($validated);
        activity()->performedOn($driver)->log("Driver {$driver->name} diperbarui");

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->bookings()->whereIn('status', ['approved', 'in_progress'])->exists()) {
            return back()->with('error', 'Driver sedang bertugas, tidak dapat dihapus.');
        }
        activity()->performedOn($driver)->log("Driver {$driver->name} dihapus");
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil dihapus.');
    }
}
