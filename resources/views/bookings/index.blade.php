@extends('layouts.app')
@section('title', 'Daftar Pemesanan')
@section('page-title', 'Pemesanan Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Daftar Pemesanan</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Kelola semua pemesanan kendaraan perusahaan</p>
    </div>
    @role('admin')
    <a href="{{ route('bookings.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
        <i class="bi bi-plus-lg"></i> Buat Pemesanan
    </a>
    @endrole
</div>

{{-- Filter Bar --}}
<div class="card mb-4">
    <div class="card-body" style="padding:20px;">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @php
                        $statusOptions = [
                            'pending'     => 'Menunggu Persetujuan',
                            'approved_l1' => 'Disetujui Level 1',
                            'approved'    => 'Disetujui',
                            'rejected'    => 'Ditolak',
                            'in_progress' => 'Sedang Berjalan',
                            'completed'   => 'Selesai',
                            'cancelled'   => 'Dibatalkan',
                        ];
                    @endphp
                    @foreach($statusOptions as $val => $label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Dari</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill" style="border-radius:8px;">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4">No. Pemesanan</th>
                        <th>Pemohon</th>
                        <th>Kendaraan</th>
                        <th>Driver</th>
                        <th>Tujuan</th>
                        <th>Tgl Berangkat</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="ps-4 fw-semibold">
                            <a href="{{ route('bookings.show', $booking) }}" class="text-decoration-none" style="color:#1e3a5f;">
                                {{ $booking->booking_number }}
                            </a>
                        </td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>
                            <div>{{ $booking->vehicle->name ?? '-' }}</div>
                            <small class="text-muted">{{ $booking->vehicle->plate_number ?? '' }}</small>
                        </td>
                        <td>{{ $booking->driver->name ?? '<em class="text-muted">Tanpa driver</em>' }}</td>
                        <td>{{ Str::limit($booking->destination, 30) }}</td>
                        <td>
                            {{ $booking->start_datetime->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ $booking->start_datetime->format('H:i') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $booking->status_badge }}">{{ $booking->status_label }}</span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" title="Detail" style="border-radius:6px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @role('admin')
                                @if($booking->status === 'pending')
                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-warning" title="Edit" style="border-radius:6px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                @endrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x" style="font-size:40px;opacity:.3;"></i>
                            <div class="mt-2">Belum ada pemesanan</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="p-3 border-top">
            {{ $bookings->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
