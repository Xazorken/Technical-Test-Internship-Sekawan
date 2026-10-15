@extends('layouts.app')
@section('title', 'Tambah Driver')
@section('page-title', 'Tambah Driver')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Tambah Driver</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Isi data pengemudi baru</p>
    </div>
    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Nama driver" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. SIM <span class="text-danger">*</span></label>
                    <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                        value="{{ old('license_number') }}" placeholder="Nomor SIM" required>
                    @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe SIM <span class="text-danger">*</span></label>
                    <select name="license_type" class="form-select @error('license_type') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['A','B1','B2','C'] as $type)
                        <option value="{{ $type }}" {{ old('license_type') == $type ? 'selected' : '' }}>SIM {{ $type }}</option>
                        @endforeach
                    </select>
                    @error('license_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tgl. Kadaluarsa SIM</label>
                    <input type="date" name="license_expiry_date" class="form-control @error('license_expiry_date') is-invalid @enderror"
                        value="{{ old('license_expiry_date') }}">
                    @error('license_expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. HP</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xx">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi Penempatan</label>
                    <select name="location_id" class="form-select">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="available" {{ old('status','available') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="on_duty" {{ old('status') == 'on_duty' ? 'selected' : '' }}>Bertugas</option>
                        <option value="off" {{ old('status') == 'off' ? 'selected' : '' }}>Libur</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
