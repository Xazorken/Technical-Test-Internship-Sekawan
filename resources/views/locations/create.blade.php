@extends('layouts.app')
@section('title', 'Tambah Lokasi')
@section('page-title', 'Tambah Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Tambah Lokasi</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Tambah lokasi pool kendaraan baru</p>
    </div>
    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('locations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Nama pool / cabang" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="pusat" {{ old('type') == 'pusat' ? 'selected' : '' }}>Pusat</option>
                        <option value="cabang" {{ old('type') == 'cabang' ? 'selected' : '' }}>Cabang</option>
                        <option value="tambang" {{ old('type') == 'tambang' ? 'selected' : '' }}>Area Tambang</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kota</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="Kota">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Region / Provinsi</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region') }}" placeholder="Provinsi">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap lokasi">{{ old('address') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label fw-semibold" for="is_active">Lokasi Aktif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
