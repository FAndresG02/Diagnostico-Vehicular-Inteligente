<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OBDRecord extends Model
{
    protected $table = 'obd_records';

    protected $fillable = [
        'vehicle_id',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function dtcCodes(): HasMany
    {
        return $this->hasMany(DtcCode::class, 'obd_record_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
