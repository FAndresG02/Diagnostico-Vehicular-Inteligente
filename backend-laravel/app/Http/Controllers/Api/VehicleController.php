<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create([
            'marca' => $request->input('marca'),
            'modelo' => $request->input('modelo'),
            'anio' => $request->input('anio'),
            'vin' => $request->input('vin'),
        ]);

        return response()->json([
            'status' => 'ok',
            'vehicle_saved' => $vehicle->toArray(),
        ], 200);
    }

    public function show(): JsonResponse
    {
        $vehicle = Vehicle::latest()->first();

        if (!$vehicle) {
            return response()->json(['exists' => false], 200);
        }

        return response()->json(new VehicleResource($vehicle), 200);
    }
}
