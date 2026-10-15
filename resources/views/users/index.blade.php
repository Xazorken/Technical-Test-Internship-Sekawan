@extends('layouts.app')
@section('title', 'Daftar User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Manajemen User</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Kelola akun pengguna sistem</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable" style="font-size:13px;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4">Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Departemen</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->position ?? '-' }}</small>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                @php
                                    $roleColors = ['admin'=>'danger','approver_l1'=>'warning','approver_l2'=>'info'];
                                    $rc = $roleColors[$role->name] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $rc }}">{{ str_replace('_', ' ', strtoupper($role->name)) }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->department ?? '-' }}</td>
                        <td>{{ $user->location->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;" title="Hapus"
                                        onclick="return confirm('Hapus user ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people" style="font-size:40px;opacity:.3;"></i>
                            <div class="mt-2">Belum ada user</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="p-3 border-top">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
