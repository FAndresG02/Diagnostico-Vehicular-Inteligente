<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OBDRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigos' => $this->dtcCodes->pluck('code'),
            'timestamp' => $this->recorded_at->toISOString(),
            'vehicle_id' => $this->vehicle_id,
        ];
    }
}
