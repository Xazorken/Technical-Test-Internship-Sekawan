<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name', 'type', 'address', 'city', 'region', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'pusat'  => 'Kantor Pusat',
            'cabang' => 'Kantor Cabang',
            'tambang'=> 'Tambang',
            default  => ucfirst($this->type),
        };
    }
}
