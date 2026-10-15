@extends('layouts.app')
@section('title', 'Edit Driver')
@section('page-title', 'Edit Driver')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Edit Driver</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Perbarui data {{ $driver->name }}</p>
    </div>
    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('drivers.update', $driver) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $driver->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. SIM <span class="text-danger">*</span></label>
                    <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                        value="{{ old('license_number', $driver->license_number) }}" required>
                    @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe SIM <span class="text-danger">*</span></label>
                    <select name="license_type" class="form-select @error('license_type') is-invalid @enderror" required>
                        @foreach(['A','B1','B2','C'] as $type)
                        <option value="{{ $type }}" {{ old('license_type', $driver->license_type) == $type ? 'selected' : '' }}>SIM {{ $type }}</option>
                        @endforeach
                    </select>
                    @error('license_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tgl. Kadaluarsa SIM</label>
                    <input type="date" name="license_expiry_date" class="form-control"
                        value="{{ old('license_expiry_date', $driver->license_expiry_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. HP</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $driver->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi Penempatan</label>
                    <select name="location_id" class="form-select">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id', $driver->location_id) == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="available" {{ old('status', $driver->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="on_duty" {{ old('status', $driver->status) == 'on_duty' ? 'selected' : '' }}>Bertugas</option>
                        <option value="off" {{ old('status', $driver->status) == 'off' ? 'selected' : '' }}>Libur</option>
                        <option value="inactive" {{ old('status', $driver->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $driver->address) }}</textarea>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-warning px-5">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
