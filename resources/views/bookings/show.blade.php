@extends('layouts.app')
@section('title', 'Detail Pemesanan #' . $booking->booking_number)
@section('page-title', 'Detail Pemesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <div class="d-flex gap-2">
        @if(in_array($booking->status, ['approved', 'in_progress']) && auth()->user()->isAdmin())
        <form action="{{ route('bookings.complete', $booking) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success btn-sm" style="border-radius:8px;"
                onclick="return confirm('Tandai pemesanan ini sebagai selesai?')">
                <i class="bi bi-check2-circle me-1"></i> Tandai Selesai
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- Booking Info --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold" style="font-size:16px;">{{ $booking->booking_number }}</span>
                    <span class="badge bg-{{ $booking->status_badge }} ms-2">{{ $booking->status_label }}</span>
                </div>
                <small class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <div class="card-body" style="padding:28px;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Kendaraan</div>
                            <div class="fw-semibold mt-1">{{ $booking->vehicle->name ?? '-' }}</div>
                            <div class="text-muted" style="font-size:13px;">{{ $booking->vehicle->plate_number ?? '' }} · {{ $booking->vehicle->type_label ?? '' }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Driver</div>
                            <div class="fw-semibold mt-1">{{ $booking->driver->name ?? 'Tanpa Driver' }}</div>
                            @if($booking->driver)
                            <div class="text-muted" style="font-size:13px;">SIM {{ $booking->driver->license_type }} · {{ $booking->driver->license_number }}</div>
                            @endif
                        </div>
                        <div class="mb-4">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Pemohon</div>
                            <div class="fw-semibold mt-1">{{ $booking->user->name ?? '-' }}</div>
                            <div class="text-muted" style="font-size:13px;">{{ $booking->user->department ?? '' }} · {{ $booking->user->position ?? '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Asal → Tujuan</div>
                            <div class="fw-semibold mt-1">{{ $booking->fromLocation->name ?? 'Tidak ditentukan' }}</div>
                            <div style="color:#0d9488;font-weight:600;">→ {{ $booking->destination }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Waktu Perjalanan</div>
                            <div class="fw-semibold mt-1">{{ $booking->start_datetime->format('d/m/Y H:i') }}</div>
                            <div class="text-muted" style="font-size:13px;">s/d {{ $booking->end_datetime->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Jumlah Penumpang</div>
                            <div class="fw-semibold mt-1">{{ $booking->passenger_count }} orang</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Keperluan / Tujuan</div>
                            <div class="mt-2 p-3" style="background:#f8fafc;border-radius:10px;font-size:14px;">{{ $booking->purpose }}</div>
                        </div>
                        @if($booking->notes)
                        <div>
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;">Catatan</div>
                            <div class="mt-2 p-3" style="background:#f8fafc;border-radius:10px;font-size:14px;">{{ $booking->notes }}</div>
                        </div>
                        @endif
                    </div>

                    @if($booking->status === 'completed')
                    <div class="col-12">
                        <hr>
                        <h6 class="fw-bold mb-3">Data Kembali</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-muted" style="font-size:12px;">KM Awal</div>
                                <div class="fw-semibold">{{ number_format($booking->odometer_start) ?? '-' }} km</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted" style="font-size:12px;">KM Akhir</div>
                                <div class="fw-semibold">{{ number_format($booking->odometer_end) ?? '-' }} km</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted" style="font-size:12px;">BBM Terpakai</div>
                                <div class="fw-semibold">{{ $booking->fuel_used ?? '-' }} Liter</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($booking->rejection_reason)
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>
                            <strong>Alasan Penolakan:</strong> {{ $booking->rejection_reason }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Panel --}}
    <div class="col-lg-4">
        {{-- Approval Progress --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-shield-check me-2"></i>Status Persetujuan</div>
            <div class="card-body" style="padding:24px;">
                <div class="timeline">
                    @foreach($booking->approvals->sortBy('level') as $approval)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-{{ $approval->status_badge }}" style="color:white;">
                            @if($approval->status === 'approved') <i class="bi bi-check-lg"></i>
                            @elseif($approval->status === 'rejected') <i class="bi bi-x-lg"></i>
                            @else <i class="bi bi-clock"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="fw-semibold" style="font-size:13px;">Level {{ $approval->level }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $approval->approver->name ?? '-' }}</div>
                            <div style="font-size:12px;">
                                <span class="badge bg-{{ $approval->status_badge }}">{{ $approval->status_label }}</span>
                            </div>
                            @if($approval->acted_at)
                            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">{{ $approval->acted_at->format('d/m/Y H:i') }}</div>
                            @endif
                            @if($approval->notes)
                            <div class="mt-1 p-2" style="background:#f1f5f9;border-radius:6px;font-size:12px;">{{ $approval->notes }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Approve/Reject Form --}}
        @php
            $user = auth()->user();
            $myLevel = $user->hasRole('approver_l1') ? 1 : ($user->hasRole('approver_l2') ? 2 : null);
            $myApproval = $myLevel ? $booking->approvals->where('approver_id', $user->id)->where('level', $myLevel)->where('status', 'pending')->first() : null;

            // Check if it's my turn
            $canAct = false;
            if ($myApproval) {
                if ($myLevel === 1) $canAct = $booking->status === 'pending';
                if ($myLevel === 2) $canAct = $booking->status === 'approved_l1';
            }
        @endphp

        @if($canAct)
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10" style="border-color: #fbbf24;">
                <i class="bi bi-bell-fill me-2 text-warning"></i>
                <strong>Aksi Diperlukan - Level {{ $myLevel }}</strong>
            </div>
            <div class="card-body" style="padding:24px;">
                <form action="{{ route('bookings.approve', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="approved"
                            class="btn btn-success flex-fill" style="border-radius:8px;">
                            <i class="bi bi-check-lg me-1"></i>Setujui
                        </button>
                        <button type="submit" name="action" value="rejected"
                            class="btn btn-danger flex-fill" style="border-radius:8px;"
                            onclick="return confirm('Yakin ingin menolak pemesanan ini?')">
                            <i class="bi bi-x-lg me-1"></i>Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
