@extends('layouts.app')
@section('title', 'Edit Pemesanan')
@section('page-title', 'Edit Pemesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Pemesanan #{{ $booking->booking_number }}</div>
            <div class="card-body" style="padding:32px;">
                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                    @csrf @method('PUT')

                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-truck me-2"></i>Kendaraan & Driver
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                            <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" {{ old('vehicle_id', $booking->vehicle_id) == $v->id ? 'selected' : '' }}>
                                    {{ $v->name }} ({{ $v->plate_number }}) - {{ $v->type_label }}
                                </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driver</label>
                            <select name="driver_id" class="form-select">
                                <option value="">-- Tanpa Driver --</option>
                                @foreach($drivers as $d)
                                <option value="{{ $d->id }}" {{ old('driver_id', $booking->driver_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} (SIM {{ $d->license_type }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi Asal</label>
                            <select name="from_location_id" class="form-select">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('from_location_id', $booking->from_location_id) == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                            <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror"
                                value="{{ old('destination', $booking->destination) }}" required>
                            @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;border-bottom:2px solid #0d9488;padding-bottom:8px;">
                        <i class="bi bi-calendar3 me-2"></i>Jadwal & Keperluan
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal & Jam Berangkat <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_datetime" class="form-control @error('start_datetime') is-invalid @enderror"
                                value="{{ old('start_datetime', $booking->start_datetime?->format('Y-m-d\TH:i')) }}" required>
                            @error('start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal & Jam Kembali <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_datetime" class="form-control @error('end_datetime') is-invalid @enderror"
                                value="{{ old('end_datetime', $booking->end_datetime?->format('Y-m-d\TH:i')) }}" required>
                            @error('end_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Penumpang <span class="text-danger">*</span></label>
                            <input type="number" name="passenger_count" class="form-control" min="1"
                                value="{{ old('passenger_count', $booking->passenger_count) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keperluan / Tujuan <span class="text-danger">*</span></label>
                            <textarea name="purpose" class="form-control @error('purpose') is-invalid @enderror" rows="3" required>{{ old('purpose', $booking->purpose) }}</textarea>
                            @error('purpose')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $booking->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <i class="bi bi-info-circle me-2 text-warning"></i>Info Pemesanan
            </div>
            <div class="card-body" style="font-size:13px;">
                <div class="mb-2"><span class="text-muted">No. Pemesanan:</span> <strong>{{ $booking->booking_number }}</strong></div>
                <div class="mb-2"><span class="text-muted">Status:</span>
                    <span class="badge bg-warning">{{ ucfirst($booking->status) }}</span>
                </div>
                <div class="mb-2"><span class="text-muted">Dibuat:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</div>
                <hr>
                <small class="text-muted">Hanya pemesanan dengan status <strong>Pending</strong> yang dapat diubah.</small>
            </div>
        </div>
    </div>
</div>
@endsection
