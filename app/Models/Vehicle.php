<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Vehicle extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name', 'brand', 'model', 'year', 'plate_number',
        'type', 'ownership', 'rental_company', 'status',
        'location_id', 'fuel_consumption', 'last_service_date',
        'next_service_date', 'service_interval_km', 'notes',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'next_service_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(VehicleService::class);
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(VehicleFuelLog::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'angkutan_orang'  => 'Angkutan Orang',
            'angkutan_barang' => 'Angkutan Barang',
            default           => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available'   => 'Tersedia',
            'in_use'      => 'Digunakan',
            'maintenance' => 'Servis',
            'inactive'    => 'Tidak Aktif',
            default       => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available'   => 'success',
            'in_use'      => 'warning',
            'maintenance' => 'info',
            'inactive'    => 'danger',
            default       => 'secondary',
        };
    }
}
