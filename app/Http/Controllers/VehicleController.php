<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('location')->latest()->paginate(15);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        return view('vehicles.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'brand'               => 'nullable|string|max:100',
            'model'               => 'nullable|string|max:100',
            'year'                => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number'        => 'required|string|unique:vehicles',
            'type'                => 'required|in:angkutan_orang,angkutan_barang',
            'ownership'           => 'required|in:milik,sewa',
            'rental_company'      => 'nullable|string|max:255',
            'status'              => 'required|in:available,in_use,maintenance,inactive',
            'location_id'         => 'nullable|exists:locations,id',
            'fuel_consumption'    => 'nullable|integer',
            'last_service_date'   => 'nullable|date',
            'next_service_date'   => 'nullable|date',
            'service_interval_km' => 'nullable|integer',
            'notes'               => 'nullable|string',
        ]);

        $vehicle = Vehicle::create($validated);

        activity()->performedOn($vehicle)->log("Kendaraan {$vehicle->plate_number} ditambahkan");

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['location', 'bookings.user', 'services', 'fuelLogs']);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $locations = Location::where('is_active', true)->get();
        return view('vehicles.edit', compact('vehicle', 'locations'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'brand'               => 'nullable|string|max:100',
            'model'               => 'nullable|string|max:100',
            'year'                => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number'        => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'type'                => 'required|in:angkutan_orang,angkutan_barang',
            'ownership'           => 'required|in:milik,sewa',
            'rental_company'      => 'nullable|string|max:255',
            'status'              => 'required|in:available,in_use,maintenance,inactive',
            'location_id'         => 'nullable|exists:locations,id',
            'fuel_consumption'    => 'nullable|integer',
            'last_service_date'   => 'nullable|date',
            'next_service_date'   => 'nullable|date',
            'service_interval_km' => 'nullable|integer',
            'notes'               => 'nullable|string',
        ]);

        $vehicle->update($validated);

        activity()->performedOn($vehicle)->log("Kendaraan {$vehicle->plate_number} diperbarui");

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->bookings()->whereIn('status', ['approved', 'in_progress'])->exists()) {
            return back()->with('error', 'Kendaraan sedang digunakan, tidak dapat dihapus.');
        }

        activity()->performedOn($vehicle)->log("Kendaraan {$vehicle->plate_number} dihapus");
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
