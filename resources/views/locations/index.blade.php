@extends('layouts.app')
@section('title', 'Daftar Lokasi')
@section('page-title', 'Manajemen Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Manajemen Lokasi</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Kelola lokasi pool kendaraan perusahaan</p>
    </div>
    <a href="{{ route('locations.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
        <i class="bi bi-plus-lg"></i> Tambah Lokasi
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable" style="font-size:13px;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4">Nama Lokasi</th>
                        <th>Tipe</th>
                        <th>Kota / Region</th>
                        <th>Kendaraan</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $loc)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $loc->name }}</div>
                            <small class="text-muted">{{ $loc->address ?? '-' }}</small>
                        </td>
                        <td>
                            @php $typeMap = ['pusat'=>['primary','Pusat'],'cabang'=>['info','Cabang'],'tambang'=>['warning','Tambang']]; [$tc,$tl] = $typeMap[$loc->type] ?? ['secondary',$loc->type]; @endphp
                            <span class="badge bg-{{ $tc }}">{{ $tl }}</span>
                        </td>
                        <td>{{ $loc->city ?? '-' }}{{ $loc->region ? ' / '.$loc->region : '' }}</td>
                        <td><span class="badge bg-secondary">{{ $loc->vehicles_count }} unit</span></td>
                        <td><span class="badge bg-secondary">{{ $loc->drivers_count }} orang</span></td>
                        <td>
                            <span class="badge {{ $loc->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $loc->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('locations.edit', $loc) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('locations.destroy', $loc) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;" title="Hapus"
                                        onclick="return confirm('Hapus lokasi ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-geo-alt" style="font-size:40px;opacity:.3;"></i>
                            <div class="mt-2">Belum ada lokasi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($locations, 'hasPages') && $locations->hasPages())
        <div class="p-3 border-top">{{ $locations->links() }}</div>
        @endif
    </div>
</div>
@endsection
