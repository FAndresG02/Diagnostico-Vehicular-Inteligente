<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ECUCommand extends Model
{
    protected $table = 'ecu_commands';

    protected $fillable = [
        'action',
        'status',
        'requested_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
