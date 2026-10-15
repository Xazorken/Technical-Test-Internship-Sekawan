<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'location'])->latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        $roles = ['admin', 'approver_l1', 'approver_l2'];
        return view('users.create', compact('locations', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'department'  => 'nullable|string|max:100',
            'position'    => 'nullable|string|max:100',
            'location_id' => 'nullable|exists:locations,id',
            'role'        => 'required|in:admin,approver_l1,approver_l2',
            'is_active'   => 'boolean',
        ]);

        $user = User::create([
            ...$validated,
            'password'  => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($validated['role']);

        activity()->performedOn($user)->log("User {$user->name} ditambahkan dengan role {$validated['role']}");

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $locations = Location::where('is_active', true)->get();
        $roles = ['admin', 'approver_l1', 'approver_l2'];
        return view('users.edit', compact('user', 'locations', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'department'  => 'nullable|string|max:100',
            'position'    => 'nullable|string|max:100',
            'location_id' => 'nullable|exists:locations,id',
            'role'        => 'required|in:admin,approver_l1,approver_l2',
            'is_active'   => 'boolean',
        ]);

        $data = collect($validated)->except(['password', 'role'])->toArray();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        activity()->performedOn($user)->log("User {$user->name} diperbarui");

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        activity()->performedOn($user)->log("User {$user->name} dihapus");
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
