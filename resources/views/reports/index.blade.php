@extends('layouts.app')
@section('title', 'Laporan Pemesanan')
@section('page-title', 'Laporan Pemesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Laporan Periodik Pemesanan</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Export dan cetak laporan pemesanan kendaraan</p>
    </div>
    <a href="{{ route('reports.export', request()->all()) }}"
       class="btn btn-success d-flex align-items-center gap-2" style="border-radius:10px;">
        <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
    </a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body" style="padding:20px;">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
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
            <div class="col-md-2">
                <label class="form-label">Kendaraan</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ request('vehicle_id') == $v->id ? 'selected':'' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill" style="border-radius:8px;">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Hasil Laporan ({{ $bookings->total() }} data)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4">No. Pemesanan</th>
                        <th>Tgl Pemesanan</th>
                        <th>Pemohon</th>
                        <th>Kendaraan</th>
                        <th>Tujuan</th>
                        <th>Tgl Berangkat</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>BBM (L)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('bookings.show', $b) }}" class="text-decoration-none fw-semibold">
                                {{ $b->booking_number }}
                            </a>
                        </td>
                        <td>{{ $b->created_at->format('d/m/Y') }}</td>
                        <td>{{ $b->user->name ?? '-' }}</td>
                        <td>
                            {{ $b->vehicle->name ?? '-' }}<br>
                            <small class="text-muted">{{ $b->vehicle->plate_number ?? '' }}</small>
                        </td>
                        <td>{{ Str::limit($b->destination, 25) }}</td>
                        <td>{{ $b->start_datetime->format('d/m/Y H:i') }}</td>
                        <td>{{ $b->end_datetime->format('d/m/Y H:i') }}</td>
                        <td><span class="badge bg-{{ $b->status_badge }}">{{ $b->status_label }}</span></td>
                        <td>{{ $b->fuel_used ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x" style="font-size:40px;opacity:.3;"></i>
                            <div class="mt-2">Tidak ada data</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="p-3 border-top">{{ $bookings->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
