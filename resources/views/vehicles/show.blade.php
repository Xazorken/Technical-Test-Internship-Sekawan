@extends('layouts.app')
@section('title', 'Detail Kendaraan')
@section('page-title', 'Detail Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">{{ $vehicle->name }}</h5>
        <p class="text-muted mb-0" style="font-size:13px;">{{ $vehicle->plate_number }} &middot; {{ $vehicle->brand }} {{ $vehicle->year }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-warning" style="border-radius:10px;">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Detail Info --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-truck me-2 text-primary"></i>Informasi Kendaraan</div>
            <div class="card-body p-4">
                <div class="row g-3" style="font-size:14px;">
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:12px;">Nama Kendaraan</div>
                        <div class="fw-semibold">{{ $vehicle->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:12px;">Nomor Plat</div>
                        <div class="fw-semibold">{{ $vehicle->plate_number }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:12px;">Merek / Model</div>
                        <div class="fw-semibold">{{ $vehicle->brand ?? '-' }} {{ $vehicle->model ?? '' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-muted" style="font-size:12px;">Tahun</div>
                        <div class="fw-semibold">{{ $vehicle->year ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted" style="font-size:12px;">Jenis</div>
                        <div class="fw-semibold">{{ $vehicle->type_label }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted" style="font-size:12px;">Kepemilikan</div>
                        <span class="badge {{ $vehicle->ownership === 'milik' ? 'bg-primary' : 'bg-warning text-dark' }}">
                            {{ $vehicle->ownership === 'milik' ? 'Milik' : 'Sewa' }}
                        </span>
                        @if($vehicle->rental_company)
                            <div style="font-size:12px;color:#64748b;">{{ $vehicle->rental_company }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:12px;">Lokasi</div>
                        <div class="fw-semibold">{{ $vehicle->location->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted" style="font-size:12px;">Status</div>
                        <span class="badge bg-{{ $vehicle->status_badge }}">{{ $vehicle->status_label }}</span>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted" style="font-size:12px;">Konsumsi BBM</div>
                        <div class="fw-semibold">{{ $vehicle->fuel_consumption ? $vehicle->fuel_consumption.' km/L' : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-tools me-2 text-warning"></i>Jadwal Servis</div>
            <div class="card-body p-4">
                <div class="row g-3" style="font-size:14px;">
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:12px;">Servis Terakhir</div>
                        <div class="fw-semibold">{{ $vehicle->last_service_date?->format('d/m/Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:12px;">Servis Berikutnya</div>
                        @if($vehicle->next_service_date)
                            <div class="fw-semibold {{ $vehicle->next_service_date->isPast() ? 'text-danger' : '' }}">
                                {{ $vehicle->next_service_date->format('d/m/Y') }}
                                @if($vehicle->next_service_date->isPast())
                                    <span class="badge bg-danger" style="font-size:10px;">Terlewat</span>
                                @elseif($vehicle->next_service_date->diffInDays(now()) <= 7)
                                    <span class="badge bg-warning text-dark" style="font-size:10px;">Segera</span>
                                @endif
                            </div>
                        @else
                            <div class="fw-semibold">-</div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:12px;">Interval Servis</div>
                        <div class="fw-semibold">{{ $vehicle->service_interval_km ? number_format($vehicle->service_interval_km).' km' : '-' }}</div>
                    </div>
                    @if($vehicle->notes)
                    <div class="col-12">
                        <div class="text-muted" style="font-size:12px;">Catatan</div>
                        <div>{{ $vehicle->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Stats --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-calendar-check me-2 text-success"></i>Riwayat Pemesanan</div>
            <div class="card-body p-3">
                @forelse($vehicle->bookings->take(5) as $b)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="font-size:13px;">
                    <div>
                        <a href="{{ route('bookings.show', $b) }}" class="text-decoration-none fw-semibold">
                            {{ $b->booking_number }}
                        </a>
                        <div class="text-muted" style="font-size:11px;">{{ $b->user->name ?? '-' }}</div>
                    </div>
                    <span class="badge bg-{{ $b->status_badge }}">{{ $b->status_label }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-3" style="font-size:13px;">
                    Belum ada riwayat pemesanan
                </div>
                @endforelse
                @if($vehicle->bookings->count() > 5)
                <div class="text-center mt-2">
                    <small class="text-muted">+{{ $vehicle->bookings->count() - 5 }} pemesanan lainnya</small>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body p-3">
                <div class="text-center py-2">
                    <div style="font-size:32px;font-weight:700;color:#1e3a5f;">{{ $vehicle->bookings->count() }}</div>
                    <div class="text-muted" style="font-size:13px;">Total Pemesanan</div>
                </div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:13px;">
                    <span class="text-muted">Selesai</span>
                    <strong>{{ $vehicle->bookings->where('status', 'completed')->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:13px;">
                    <span class="text-muted">Aktif</span>
                    <strong>{{ $vehicle->bookings->whereIn('status', ['approved','in_progress'])->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
