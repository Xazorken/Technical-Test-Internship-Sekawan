@extends('layouts.app')
@section('title', 'Daftar Kendaraan')
@section('page-title', 'Data Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Data Kendaraan</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Kelola kendaraan milik dan sewa perusahaan</p>
    </div>
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
        <i class="bi bi-plus-lg"></i> Tambah Kendaraan
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @php
        $statuses = ['available' => ['Tersedia','success','truck-front-fill'],
                     'in_use' => ['Digunakan','warning','truck'],
                     'maintenance' => ['Servis','info','tools'],
                     'inactive' => ['Tidak Aktif','danger','x-circle']];
    @endphp
    @foreach($statuses as $status => [$label, $color, $icon])
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="icon-box bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
            <div class="stat-value">{{ $vehicles->where('status', $status)->count() }}</div>
            <div class="stat-label">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable" style="font-size:13px;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4">Nama / Plat</th>
                        <th>Jenis</th>
                        <th>Kepemilikan</th>
                        <th>Lokasi</th>
                        <th>BBM (km/L)</th>
                        <th>Servis Berikutnya</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $v)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $v->name }}</div>
                            <small class="text-muted">{{ $v->plate_number }} · {{ $v->brand }} {{ $v->year }}</small>
                        </td>
                        <td>{{ $v->type_label }}</td>
                        <td>
                            <span class="badge {{ $v->ownership === 'milik' ? 'bg-primary' : 'bg-warning text-dark' }}">
                                {{ $v->ownership === 'milik' ? 'Milik' : 'Sewa' }}
                            </span>
                            @if($v->rental_company)
                            <div style="font-size:11px;color:#64748b;">{{ $v->rental_company }}</div>
                            @endif
                        </td>
                        <td>{{ $v->location->name ?? '-' }}</td>
                        <td>{{ $v->fuel_consumption ? $v->fuel_consumption . ' km/L' : '-' }}</td>
                        <td>
                            @if($v->next_service_date)
                                <span class="{{ $v->next_service_date->isPast() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $v->next_service_date->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $v->status_badge }}">{{ $v->status_label }}</span></td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('vehicles.show', $v) }}" class="btn btn-sm btn-outline-info" style="border-radius:6px;" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('vehicles.edit', $v) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('vehicles.destroy', $v) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;" title="Hapus"
                                        onclick="return confirm('Hapus kendaraan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-truck" style="font-size:40px;opacity:.3;"></i>
                            <div class="mt-2">Belum ada kendaraan</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($vehicles, 'hasPages') && $vehicles->hasPages())
        <div class="p-3 border-top">{{ $vehicles->links() }}</div>
        @endif
    </div>
</div>
@endsection
