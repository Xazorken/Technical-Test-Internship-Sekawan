@extends('layouts.app')
@section('title', 'Edit Lokasi')
@section('page-title', 'Edit Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Edit Lokasi</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Perbarui data {{ $location->name }}</p>
    </div>
    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('locations.update', $location) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $location->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="pusat" {{ old('type', $location->type) == 'pusat' ? 'selected' : '' }}>Pusat</option>
                        <option value="cabang" {{ old('type', $location->type) == 'cabang' ? 'selected' : '' }}>Cabang</option>
                        <option value="tambang" {{ old('type', $location->type) == 'tambang' ? 'selected' : '' }}>Area Tambang</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kota</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $location->city) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Region / Provinsi</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region', $location->region) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $location->address) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Lokasi Aktif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-warning px-5">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
