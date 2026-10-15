<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleService extends Model
{
    protected $fillable = [
        'vehicle_id', 'service_date', 'service_type',
        'description', 'odometer', 'cost', 'workshop',
    ];

    protected $casts = ['service_date' => 'date'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
