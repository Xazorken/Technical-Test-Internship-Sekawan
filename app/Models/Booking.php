<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Booking extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'booking_number', 'user_id', 'vehicle_id', 'driver_id',
        'from_location_id', 'destination', 'purpose',
        'start_datetime', 'end_datetime', 'passenger_count',
        'status', 'odometer_start', 'odometer_end', 'fuel_used',
        'notes', 'rejection_reason',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public static function generateBookingNumber(): string
    {
        $prefix = 'BK';
        $date   = now()->format('Ymd');
        $last   = static::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(BookingApproval::class);
    }

    public function approvalLevel1(): HasMany
    {
        return $this->hasMany(BookingApproval::class)->where('level', 1);
    }

    public function approvalLevel2(): HasMany
    {
        return $this->hasMany(BookingApproval::class)->where('level', 2);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'Menunggu Persetujuan',
            'approved_l1' => 'Disetujui Level 1',
            'approved'    => 'Disetujui',
            'rejected'    => 'Ditolak',
            'in_progress' => 'Sedang Berjalan',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
            default       => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'warning',
            'approved_l1' => 'info',
            'approved'    => 'primary',
            'rejected'    => 'danger',
            'in_progress' => 'success',
            'completed'   => 'secondary',
            'cancelled'   => 'dark',
            default       => 'secondary',
        };
    }

    public function getDurationAttribute(): string
    {
        return $this->start_datetime->diffForHumans($this->end_datetime, true);
    }
}
