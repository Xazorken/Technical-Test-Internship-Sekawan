@extends('layouts.app')
@section('title', 'Activity Log')
@section('page-title', 'Log Aktivitas Sistem')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1e3a5f;">Log Aktivitas Sistem</h5>
        <p class="text-muted mb-0" style="font-size:13px;">Riwayat seluruh aktivitas pengguna dalam sistem</p>
    </div>
    <span class="badge bg-secondary fs-6">{{ $logs->total() }} entri</span>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div style="overflow-x:auto;">
            <table id="activityTable" style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f1f5f9;border-bottom:2px solid #e2e8f0;">
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#475569;white-space:nowrap;">Waktu</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#475569;">Pengguna</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#475569;">Aktivitas</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#475569;">Objek</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px 16px;vertical-align:top;white-space:nowrap;color:#64748b;">
                            {{ $log->created_at->format('d/m/Y') }}<br>
                            <small>{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td style="padding:12px 16px;vertical-align:top;">
                            @if($log->causer)
                                <div style="font-weight:600;color:#1e3a5f;">{{ $log->causer->name }}</div>
                                <small style="color:#94a3b8;">{{ $log->causer->email }}</small>
                            @else
                                <span style="color:#94a3b8;">System</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;vertical-align:top;max-width:350px;">
                            <span style="display:inline-block;padding:3px 10px;border-radius:20px;background:#eff6ff;color:#1e40af;font-size:12px;font-weight:500;">
                                {{ $log->description }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;vertical-align:top;">
                            @if($log->subject_type)
                                <span style="display:inline-block;padding:2px 8px;border-radius:4px;background:#f1f5f9;color:#475569;font-size:12px;">
                                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                </span>
                            @else
                                <span style="color:#cbd5e1;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:60px 16px;text-align:center;color:#94a3b8;">
                            Belum ada log aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div style="padding:16px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <small style="color:#94a3b8;">
                Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} data
            </small>
            <div>
                @if($logs->onFirstPage())
                    <span style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;color:#cbd5e1;margin-right:4px;">&#8249; Prev</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;color:#1e3a5f;text-decoration:none;margin-right:4px;">&#8249; Prev</a>
                @endif
                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if($page == $logs->currentPage())
                        <span style="padding:6px 12px;background:#1e3a5f;color:#fff;border-radius:6px;margin-right:2px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;color:#1e3a5f;text-decoration:none;margin-right:2px;">{{ $page }}</a>
                    @endif
                @endforeach
                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;color:#1e3a5f;text-decoration:none;margin-left:2px;">Next &#8250;</a>
                @else
                    <span style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;color:#cbd5e1;margin-left:2px;">Next &#8250;</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
