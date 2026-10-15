@extends('layouts.app')
@section('title', 'Daftar Driver')
@section('page-title', 'Data Driver')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Data Driver</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Kelola data pengemudi kendaraan perusahaan</p>
    </div>
    <a href="{{ route('drivers.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
        <i class="bi bi-plus-lg"></i> Tambah Driver
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @php
        $statuses = [
            'available' => ['Tersedia','success','person-check-fill'],
            'on_duty'   => ['Bertugas','warning','person-walking'],
            'off'       => ['Libur','info','moon-fill'],
            'inactive'  => ['Tidak Aktif','danger','person-x-fill'],
        ];
    @endphp
    @foreach($statuses as $status => [$label, $color, $icon])
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="icon-box bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
            <div class="stat-value">{{ $drivers->where('status', $status)->count() }}</div>
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
                        <th class="ps-4">Nama Driver</th>
                        <th>No. SIM</th>
                        <th>Tipe SIM</th>
                        <th>Exp. SIM</th>
                        <th>No. HP</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $driver->name }}</div>
                            <small class="text-muted">{{ $driver->address ?? '-' }}</small>
                        </td>
                        <td>{{ $driver->license_number }}</td>
                        <td><span class="badge bg-secondary">SIM {{ $driver->license_type }}</span></td>
                        <td>
                            @if($driver->license_expiry_date)
                                <div class="{{ $driver->license_expiry_date->isPast() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $driver->license_expiry_date->format('d/m/Y') }}
                                </div>
                                @if($driver->license_expiry_date->isPast())
                                    <span class="badge bg-danger" style="font-size:10px;">Kadaluarsa</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $driver->phone ?? '-' }}</td>
                        <td>{{ $driver->location->name ?? '-' }}</td>
                        <td>
                            @php
                                $statusMap = ['available'=>['success','Tersedia'],'on_duty'=>['warning','Bertugas'],'off'=>['info','Libur'],'inactive'=>['danger','Tidak Aktif']];
                                [$sc, $sl] = $statusMap[$driver->status] ?? ['secondary', $driver->status];
                            @endphp
                            <span class="badge bg-{{ $sc }}">{{ $sl }}</span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;" title="Hapus"
                                        onclick="return confirm('Hapus driver ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x" style="font-size:40px;opacity:.3;"></i>
                            <div class="mt-2">Belum ada driver</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($drivers, 'hasPages') && $drivers->hasPages())
        <div class="p-3 border-top">{{ $drivers->links() }}</div>
        @endif
    </div>
</div>
@endsection
