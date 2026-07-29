<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmCommandRequest;
use App\Http\Resources\ECUCommandResource;
use App\Models\ECUCommand;
use Illuminate\Http\JsonResponse;

class ECUCommandController extends Controller
{
    public function clearDtc(): JsonResponse
    {
        $command = ECUCommand::create([
            'action' => 'CLEAR_DTC',
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function status(): JsonResponse
    {
        $command = ECUCommand::latest()->first();

        if (!$command) {
            return response()->json(['exists' => false], 200);
        }

        return response()->json(new ECUCommandResource($command), 200);
    }

    public function confirm(ConfirmCommandRequest $request): JsonResponse
    {
        $status = $request->input('status', 'error');

        ECUCommand::create([
            'action' => null,
            'status' => $status,
            'completed_at' => now(),
        ]);

        return response()->json(['status' => 'updated'], 200);
    }
}
