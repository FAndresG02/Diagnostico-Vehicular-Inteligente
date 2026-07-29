<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DtcCode extends Model
{
    protected $fillable = [
        'obd_record_id',
        'code',
    ];

    public function obdRecord(): BelongsTo
    {
        return $this->belongsTo(OBDRecord::class, 'obd_record_id');
    }
}
