<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount(['vehicles', 'drivers'])->latest()->paginate(15);
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|in:pusat,cabang,tambang',
            'address'   => 'nullable|string',
            'city'      => 'nullable|string|max:100',
            'region'    => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $location = Location::create([...$validated, 'is_active' => $request->boolean('is_active', true)]);
        activity()->performedOn($location)->log("Lokasi {$location->name} ditambahkan");

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|in:pusat,cabang,tambang',
            'address'   => 'nullable|string',
            'city'      => 'nullable|string|max:100',
            'region'    => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $location->update([...$validated, 'is_active' => $request->boolean('is_active')]);
        activity()->performedOn($location)->log("Lokasi {$location->name} diperbarui");

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        if ($location->vehicles()->count() || $location->drivers()->count()) {
            return back()->with('error', 'Lokasi masih memiliki kendaraan/driver, tidak dapat dihapus.');
        }
        activity()->performedOn($location)->log("Lokasi {$location->name} dihapus");
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
