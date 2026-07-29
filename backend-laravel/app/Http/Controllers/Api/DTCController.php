<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DtcCode;
use App\Models\OBDRecord;
use App\Services\DTCService;
use Illuminate\Http\JsonResponse;

class DTCController extends Controller
{
    public function __construct(
        private readonly DTCService $dtcService,
    ) {}

    public function destroy(string $code): JsonResponse
    {
        $code = $this->dtcService->clean($code);

        if (!$this->dtcService->isValid($code)) {
            return response()->json(['error' => 'Codigo DTC invalido'], 400);
        }

        $affectedRecords = DtcCode::where('code', $code)->get();
        $updatedDocs = 0;
        $removedDocs = 0;

        foreach ($affectedRecords->groupBy('obd_record_id') as $recordId => $codes) {
            $codes->each->delete();

            $remaining = DtcCode::where('obd_record_id', $recordId)->count();

            if ($remaining === 0) {
                OBDRecord::where('id', $recordId)->delete();
                $removedDocs++;
            } else {
                $updatedDocs++;
            }
        }

        return response()->json([
            'status' => 'ok',
            'deleted_code' => $code,
            'updated_docs' => $updatedDocs,
            'removed_empty_docs' => $removedDocs,
        ], 200);
    }
}
