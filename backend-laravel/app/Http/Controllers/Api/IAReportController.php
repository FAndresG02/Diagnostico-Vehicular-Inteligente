<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IAReportResource;
use App\Models\IAReport;
use App\Models\Vehicle;
use App\Services\DTCService;
use App\Services\IAReportService;
use Illuminate\Http\JsonResponse;

class IAReportController extends Controller
{
    public function __construct(
        private readonly IAReportService $reportService,
        private readonly DTCService $dtcService,
    ) {}

    public function generate(string $code): JsonResponse
    {
        $vehicle = Vehicle::latest()->first();

        if (!$vehicle) {
            return response()->json(['error' => 'No hay vehiculo guardado'], 400);
        }

        $code = $this->dtcService->clean($code);

        if (!$this->dtcService->isValid($code)) {
            return response()->json(['error' => 'Codigo DTC invalido'], 400);
        }

        $report = $this->reportService->generate($code, $vehicle);

        $iaReport = IAReport::create([
            'dtc_code' => $code,
            'vehicle_id' => $vehicle->id,
            'report' => $report,
        ]);

        return response()->json([
            'codigo' => $code,
            'vehiculo' => $vehicle->toInfoArray(),
            'informe' => $report,
        ], 200);
    }

    public function index(): JsonResponse
    {
        $reports = IAReport::with('vehicle')
            ->latest()
            ->get();

        return response()->json([
            'count' => $reports->count(),
            'reports' => IAReportResource::collection($reports),
        ], 200);
    }

    public function destroyByCode(string $code): JsonResponse
    {
        $code = $this->dtcService->clean($code);

        if (!$this->dtcService->isValid($code)) {
            return response()->json(['error' => 'Codigo DTC invalido'], 400);
        }

        $deleted = IAReport::where('dtc_code', $code)->delete();

        return response()->json([
            'status' => 'ok',
            'deleted_code' => $code,
            'deleted_reports' => $deleted,
        ], 200);
    }

    public function destroyAll(): JsonResponse
    {
        $deleted = IAReport::query()->delete();

        return response()->json([
            'status' => 'ok',
            'deleted_reports' => $deleted,
        ], 200);
    }
}
