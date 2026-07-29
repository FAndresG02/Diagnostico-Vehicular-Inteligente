<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IAReport extends Model
{
    protected $table = 'ia_reports';

    protected $fillable = [
        'dtc_code',
        'vehicle_id',
        'report',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
