<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OBDRecord;
use App\Services\DTCService;
use App\Services\PushNotificationService;
use App\Services\SimulationService;
use Illuminate\Http\JsonResponse;

class SimulationController extends Controller
{
    public function __construct(
        private readonly SimulationService $simulationService,
        private readonly DTCService $dtcService,
        private readonly PushNotificationService $notificationService,
    ) {}

    public function simulate(): JsonResponse
    {
        $raw = $this->simulationService->generateRandom();
        $cleaned = $this->dtcService->cleanList([$raw]);

        $record = OBDRecord::create([
            'recorded_at' => now(),
        ]);

        $record->dtcCodes()->createMany(
            array_map(fn ($code) => ['code' => $code], $cleaned)
        );

        $this->notificationService->sendDtcNotification($cleaned);

        return response()->json([
            'status' => 'simulated',
            'generated_raw' => $raw,
            'generated_cleaned' => $cleaned,
        ], 200);
    }

    public function createSpecific(string $code): JsonResponse
    {
        $cleaned = $this->dtcService->cleanList([$code]);

        if (empty($cleaned)) {
            return response()->json(['error' => 'Codigo DTC invalido'], 400);
        }

        $record = OBDRecord::create([
            'recorded_at' => now(),
        ]);

        $record->dtcCodes()->createMany(
            array_map(fn ($c) => ['code' => $c], $cleaned)
        );

        $this->notificationService->sendDtcNotification($cleaned);

        return response()->json([
            'status' => 'simulated',
            'received_raw' => $code,
            'generated_cleaned' => $cleaned,
        ], 200);
    }
}
