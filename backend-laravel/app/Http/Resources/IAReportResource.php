<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IAReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->dtc_code,
            'vehiculo' => $this->whenLoaded('vehicle', fn() => [
                'marca' => $this->vehicle->marca,
                'modelo' => $this->vehicle->modelo,
                'anio' => $this->vehicle->anio,
                'vin' => $this->vehicle->vin,
            ]),
            'informe' => $this->report,
            'timestamp' => $this->created_at->toISOString(),
        ];
    }
}
