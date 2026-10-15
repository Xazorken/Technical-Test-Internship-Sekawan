@extends('layouts.app')
@section('title', 'Buat Pemesanan Baru')
@section('page-title', 'Buat Pemesanan Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2 text-primary"></i>Form Pemesanan Kendaraan</div>
            <div class="card-body" style="padding:32px;">
                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-truck me-2"></i>Kendaraan & Driver
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected':'' }}>
                                    {{ $v->name }} ({{ $v->plate_number }}) - {{ $v->type_label }}
                                    @if($v->location) · {{ $v->location->name }} @endif
                                </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driver</label>
                            <select name="driver_id" class="form-select @error('driver_id') is-invalid @enderror">
                                <option value="">-- Tanpa Driver --</option>
                                @foreach($drivers as $d)
                                <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected':'' }}>
                                    {{ $d->name }} (SIM {{ $d->license_type }})
                                </option>
                                @endforeach
                            </select>
                            @error('driver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-geo-alt me-2"></i>Rute & Waktu
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Lokasi Asal</label>
                            <select name="from_location_id" class="form-select @error('from_location_id') is-invalid @enderror">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($locations as $l)
                                <option value="{{ $l->id }}" {{ old('from_location_id') == $l->id ? 'selected':'' }}>
                                    {{ $l->name }} ({{ $l->type_label }})
                                </option>
                                @endforeach
                            </select>
                            @error('from_location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                            <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror"
                                value="{{ old('destination') }}" placeholder="Kota/Lokasi tujuan..." required>
                            @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal & Jam Berangkat <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_datetime" class="form-control @error('start_datetime') is-invalid @enderror"
                                value="{{ old('start_datetime') }}" required>
                            @error('start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estimasi Kembali <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_datetime" class="form-control @error('end_datetime') is-invalid @enderror"
                                value="{{ old('end_datetime') }}" required>
                            @error('end_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Penumpang <span class="text-danger">*</span></label>
                            <input type="number" name="passenger_count" class="form-control @error('passenger_count') is-invalid @enderror"
                                value="{{ old('passenger_count', 1) }}" min="1" required>
                            @error('passenger_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-file-text me-2"></i>Keperluan & Persetujuan
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Keperluan / Tujuan <span class="text-danger">*</span></label>
                            <textarea name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                                rows="4" placeholder="Jelaskan keperluan penggunaan kendaraan..." required>{{ old('purpose') }}</textarea>
                            @error('purpose')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penyetuju Level 1 <span class="text-danger">*</span></label>
                            <select name="approver_l1_id" class="form-select @error('approver_l1_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Approver L1 --</option>
                                @foreach($approversL1 as $a)
                                <option value="{{ $a->id }}" {{ old('approver_l1_id') == $a->id ? 'selected':'' }}>
                                    {{ $a->name }} ({{ $a->position ?? 'Approver L1' }})
                                </option>
                                @endforeach
                            </select>
                            @error('approver_l1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penyetuju Level 2 <span class="text-danger">*</span></label>
                            <select name="approver_l2_id" class="form-select @error('approver_l2_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Approver L2 --</option>
                                @foreach($approversL2 as $a)
                                <option value="{{ $a->id }}" {{ old('approver_l2_id') == $a->id ? 'selected':'' }}>
                                    {{ $a->name }} ({{ $a->position ?? 'Approver L2' }})
                                </option>
                                @endforeach
                            </select>
                            @error('approver_l2_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary px-4" style="border-radius:10px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-5" style="border-radius:10px;">
                            <i class="bi bi-send me-2"></i>Kirim Pemesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#1e3a5f,#0d9488);color:white;">
            <div class="card-body" style="padding:28px;">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                <ul style="font-size:13px;line-height:1.8;padding-left:16px;">
                    <li>Pemesanan memerlukan <strong>persetujuan 2 level</strong> sebelum kendaraan dapat digunakan.</li>
                    <li>Kendaraan harus dalam status <strong>Tersedia</strong>.</li>
                    <li>Pastikan waktu berangkat <strong>lebih dari 1 jam</strong> dari sekarang.</li>
                    <li>Pilih Approver Level 1 terlebih dahulu (Kepala Pool), lalu Level 2 (Manager).</li>
                </ul>
                <div class="mt-3" style="background:rgba(255,255,255,.1);border-radius:10px;padding:14px;">
                    <div style="font-size:12px;opacity:.8;">Alur Persetujuan:</div>
                    <div style="font-size:13px;margin-top:8px;">
                        <span class="badge bg-warning text-dark">Pengajuan</span>
                        <i class="bi bi-arrow-right mx-2"></i>
                        <span class="badge bg-info">Level 1</span>
                        <i class="bi bi-arrow-right mx-2"></i>
                        <span class="badge bg-primary">Level 2</span>
                        <i class="bi bi-arrow-right mx-2"></i>
                        <span class="badge bg-success">Approved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
