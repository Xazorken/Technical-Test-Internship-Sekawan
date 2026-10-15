@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Edit User</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Perbarui data {{ $user->name }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->roles->first()?->name) == $role ? 'selected' : '' }}>
                            {{ str_replace('_', ' ', strtoupper($role)) }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. HP</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Lokasi</label>
                    <select name="location_id" class="form-select">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id', $user->location_id) == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Departemen</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jabatan</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $user->position) }}">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">User Aktif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-warning px-5">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
