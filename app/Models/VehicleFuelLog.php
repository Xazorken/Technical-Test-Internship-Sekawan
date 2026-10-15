<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleFuelLog extends Model
{
    protected $fillable = [
        'vehicle_id', 'booking_id', 'date', 'amount_liter',
        'cost_per_liter', 'total_cost', 'odometer', 'fuel_type', 'recorded_by',
    ];

    protected $casts = ['date' => 'date'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
