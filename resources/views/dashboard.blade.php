@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
@endsection

@section('content')

{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-xl-2 col-sm-4 col-6">
        <div class="stat-card">
            <div class="icon-box" style="background:#dbeafe;color:#1d4ed8;">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['total_bookings'] }}</div>
            <div class="stat-label">Total Pemesanan</div>
        </div>
    </div>
    <div class="col-xl-2 col-sm-4 col-6">
        <div class="stat-card">
            <div class="icon-box" style="background:#fef3c7;color:#d97706;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Menunggu Approval</div>
        </div>
    </div>
    <div class="col-xl-2 col-sm-4 col-6">
        <div class="stat-card">
            <div class="icon-box" style="background:#d1fae5;color:#065f46;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
            <div class="stat-label">Disetujui</div>
        </div>
    </div>
    <div class="col-xl-2 col-sm-4 col-6">
        <div class="stat-card">
            <div class="icon-box" style="background:#ede9fe;color:#5b21b6;">
                <i class="bi bi-truck-front-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">Sedang Berjalan</div>
        </div>
    </div>
    <div class="col-xl-2 col-sm-4 col-6">
        <div class="stat-card">
            <div class="icon-box" style="background:#f0fdf4;color:#15803d;">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['completed'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="col-xl-2 col-sm-4 col-6">
        <div class="stat-card">
            <div class="icon-box" style="background:#ccfbf1;color:#0d9488;">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-value">{{ $stats['vehicles'] }}</div>
            <div class="stat-label">Kendaraan Tersedia</div>
        </div>
    </div>
</div>

@if(isset($stats['my_pending']) && $stats['my_pending'] > 0)
<div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-bell-fill me-3 fs-5"></i>
    <div>
        Anda memiliki <strong>{{ $stats['my_pending'] }} pemesanan</strong> yang menunggu persetujuan Anda.
        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="alert-link ms-1">Lihat sekarang →</a>
    </div>
</div>
@endif

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Tren Pemesanan (6 Bulan Terakhir)</span>
            </div>
            <div class="card-body" style="padding: 24px;">
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2 text-success"></i>Status Pemesanan
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="padding:24px;">
                <canvas id="statusChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Vehicle Usage + Recent Bookings --}}
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-truck-front me-2 text-info"></i>Pemakaian per Jenis Kendaraan
            </div>
            <div class="card-body" style="padding:24px;">
                <canvas id="vehicleChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Pemesanan Terbaru</span>
                <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:13px;">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th class="ps-4">No. Pemesanan</th>
                                <th>Pemohon</th>
                                <th>Kendaraan</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $b)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('bookings.show', $b) }}" class="text-decoration-none fw-semibold">
                                        {{ $b->booking_number }}
                                    </a>
                                </td>
                                <td>{{ $b->user->name ?? '-' }}</td>
                                <td>{{ $b->vehicle->name ?? '-' }}</td>
                                <td>{{ Str::limit($b->destination, 25) }}</td>
                                <td>
                                    <span class="badge bg-{{ $b->status_badge }}">{{ $b->status_label }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pemesanan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const primaryColor = '#1e3a5f';
const secondaryColor = '#0d9488';
const accentColor = '#f59e0b';

// Monthly Booking Chart
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Jumlah Pemesanan',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(13,148,136,0.15)',
            borderColor: secondaryColor,
            borderWidth: 2,
            borderRadius: 8,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});

// Status Distribution Chart
const statusData = {!! json_encode($statusData->pluck('total')->toArray()) !!};
const statusLabels = {!! json_encode($statusData->map(fn($s) => ucfirst($s->status))->toArray()) !!};
const statusColors = ['#f59e0b','#3b82f6','#10b981','#ef4444','#8b5cf6','#64748b','#1e293b'];

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{ data: statusData, backgroundColor: statusColors, borderWidth: 0, hoverOffset: 8 }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 12 } }
        }
    }
});

// Vehicle Usage Chart
const vehicleLabels = {!! json_encode($vehicleUsage->map(fn($v) => $v->type === 'angkutan_orang' ? 'Angkutan Orang' : 'Angkutan Barang')->toArray()) !!};
const vehicleData = {!! json_encode($vehicleUsage->pluck('total')->toArray()) !!};

new Chart(document.getElementById('vehicleChart'), {
    type: 'doughnut',
    data: {
        labels: vehicleLabels,
        datasets: [{ data: vehicleData, backgroundColor: [secondaryColor, accentColor], borderWidth: 0, hoverOffset: 8 }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 13 }, padding: 16 } }
        }
    }
});
</script>
@endsection
