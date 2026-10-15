<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'license_number', 'license_type', 'license_expiry_date',
        'phone', 'address', 'location_id', 'status', 'is_active',
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
        'is_active'           => 'boolean',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'Tersedia',
            'on_duty'   => 'Bertugas',
            'off'       => 'Off',
            'inactive'  => 'Tidak Aktif',
            default     => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available' => 'success',
            'on_duty'   => 'warning',
            'off'       => 'secondary',
            'inactive'  => 'danger',
            default     => 'secondary',
        };
    }
}
