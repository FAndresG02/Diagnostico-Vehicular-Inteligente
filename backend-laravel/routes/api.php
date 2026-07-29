<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OBDController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\SimulationController;
use App\Http\Controllers\Api\IAReportController;
use App\Http\Controllers\Api\DTCController;
use App\Http\Controllers\Api\ECUCommandController;

Route::post('/obd', [OBDController::class, 'store']);
Route::get('/data', [OBDController::class, 'index']);
Route::post('/borrar_dtc_todos', [OBDController::class, 'destroyAll']);

Route::get('/simulate', [SimulationController::class, 'simulate']);
Route::get('/create_dtc/{codigo}', [SimulationController::class, 'createSpecific']);

Route::get('/vehicle', [VehicleController::class, 'show']);
Route::post('/vehicle', [VehicleController::class, 'store']);

Route::get('/ia/{codigo}', [IAReportController::class, 'generate']);
Route::get('/ia_reports', [IAReportController::class, 'index']);
Route::delete('/ia_reports/{codigo}', [IAReportController::class, 'destroyByCode']);
Route::delete('/ia_reports', [IAReportController::class, 'destroyAll']);

Route::delete('/delete_dtc/{codigo}', [DTCController::class, 'destroy']);

Route::post('/commands/clear_dtc', [ECUCommandController::class, 'clearDtc']);
Route::get('/commands/status', [ECUCommandController::class, 'status']);
Route::post('/commands/confirm', [ECUCommandController::class, 'confirm']);
