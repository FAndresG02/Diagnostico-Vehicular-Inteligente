<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOBDRequest;
use App\Http\Resources\OBDRecordResource;
use App\Models\DtcCode;
use App\Models\OBDRecord;
use App\Services\DTCService;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;

class OBDController extends Controller
{
    public function __construct(
        private readonly DTCService $dtcService,
        private readonly PushNotificationService $notificationService,
    ) {}

    public function store(StoreOBDRequest $request): JsonResponse
    {
        $cleaned = $this->dtcService->cleanList($request->input('dtc'));

        if (empty($cleaned)) {
            return response()->json([
                'error' => 'No se recibieron codigos validos',
            ], 400);
        }

        $record = OBDRecord::create([
            'recorded_at' => now(),
        ]);

        $codes = array_map(fn ($code) => ['code' => $code], $cleaned);

        $record->dtcCodes()->createMany($codes);

        $this->notificationService->sendDtcNotification($cleaned);

        return response()->json([
            'status' => 'ok',
            'saved' => [
                'dtc' => $cleaned,
                'timestamp' => $record->recorded_at->toISOString(),
            ],
        ], 200);
    }

    public function index(): JsonResponse
    {
        $records = OBDRecord::with('dtcCodes')->get();

        $registros = [];

        foreach ($records as $record) {
            foreach ($record->dtcCodes as $dtc) {
                $registros[] = [
                    'codigo' => $dtc->code,
                    'timestamp' => $record->recorded_at->toISOString(),
                ];
            }
        }

        return response()->json([
            'dtc_registros' => $registros,
            'count' => count($registros),
        ], 200);
    }

    public function destroyAll(): JsonResponse
    {
        $count = OBDRecord::count();

        DtcCode::query()->delete();
        OBDRecord::query()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Historial eliminado.',
            'deleted_count' => $count,
        ], 200);
    }
}
