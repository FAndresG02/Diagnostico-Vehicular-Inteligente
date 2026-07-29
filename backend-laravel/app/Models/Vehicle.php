<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'marca',
        'modelo',
        'anio',
        'vin',
    ];

    public function obdRecords(): HasMany
    {
        return $this->hasMany(OBDRecord::class);
    }

    public function iaReports(): HasMany
    {
        return $this->hasMany(IAReport::class);
    }

    public function toInfoArray(): array
    {
        return [
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'anio' => $this->anio,
            'vin' => $this->vin,
        ];
    }
}
