<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ECUCommandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'exists' => true,
            'action' => $this->action,
            'status' => $this->status,
            'requested_at' => $this->requested_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
        ];
    }
}
