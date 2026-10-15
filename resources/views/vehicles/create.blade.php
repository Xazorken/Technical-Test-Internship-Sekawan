@extends('layouts.app')
@section('title', 'Tambah Kendaraan')
@section('page-title', 'Tambah Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Tambah Kendaraan</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Daftarkan kendaraan baru ke dalam sistem</p>
    </div>
    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('vehicles.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                {{-- Informasi Dasar --}}
                <div class="col-12">
                    <h6 class="fw-bold mb-0" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-truck me-2"></i>Informasi Kendaraan
                    </h6>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Kendaraan <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Contoh: Toyota Hilux Double Cabin" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Merek</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Toyota, Isuzu, dll">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="{{ old('model') }}" placeholder="Hilux, ELF, dll">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" class="form-control" value="{{ old('year') }}"
                        min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Plat <span class="text-danger">*</span></label>
                    <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
                        value="{{ old('plate_number') }}" placeholder="DT 1234 AB" required
                        style="text-transform:uppercase;">
                    @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="angkutan_orang" {{ old('type') == 'angkutan_orang' ? 'selected' : '' }}>Angkutan Orang</option>
                        <option value="angkutan_barang" {{ old('type') == 'angkutan_barang' ? 'selected' : '' }}>Angkutan Barang</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kepemilikan <span class="text-danger">*</span></label>
                    <select name="ownership" id="ownership" class="form-select @error('ownership') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="milik" {{ old('ownership') == 'milik' ? 'selected' : '' }}>Milik Perusahaan</option>
                        <option value="sewa" {{ old('ownership') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                    </select>
                    @error('ownership')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6" id="rental_company_field" style="{{ old('ownership') == 'sewa' ? '' : 'display:none;' }}">
                    <label class="form-label">Nama Perusahaan Sewa</label>
                    <input type="text" name="rental_company" class="form-control" value="{{ old('rental_company') }}"
                        placeholder="Nama vendor penyewa">
                </div>

                {{-- Lokasi & Status --}}
                <div class="col-12 mt-2">
                    <h6 class="fw-bold mb-0" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-geo-alt me-2"></i>Lokasi & Status
                    </h6>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lokasi Penempatan</label>
                    <select name="location_id" class="form-select">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="available" {{ old('status','available') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>Digunakan</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Dalam Servis</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Konsumsi BBM (km/L)</label>
                    <input type="number" name="fuel_consumption" class="form-control" value="{{ old('fuel_consumption') }}" min="1">
                </div>

                {{-- Servis --}}
                <div class="col-12 mt-2">
                    <h6 class="fw-bold mb-0" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-tools me-2"></i>Informasi Servis
                    </h6>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tgl Servis Terakhir</label>
                    <input type="date" name="last_service_date" class="form-control" value="{{ old('last_service_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl Servis Berikutnya</label>
                    <input type="date" name="next_service_date" class="form-control" value="{{ old('next_service_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Interval Servis (km)</label>
                    <input type="number" name="service_interval_km" class="form-control"
                        value="{{ old('service_interval_km') }}" placeholder="5000">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan tentang kendaraan ini">{{ old('notes') }}</textarea>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end pt-2 border-top">
                    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> Simpan Kendaraan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('ownership').addEventListener('change', function() {
        document.getElementById('rental_company_field').style.display =
            this.value === 'sewa' ? '' : 'none';
    });
</script>
@endsection
