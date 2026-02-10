<?php

use App\Http\Controllers\Api\V1\AuthTokenController;
use App\Http\Controllers\Api\V1\PipelineMetricsController;
use App\Http\Controllers\Api\V1\PositionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/auth/tokens', [AuthTokenController::class, 'store'])->name('api.v1.auth.tokens.store');
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::get('/metrics/pipeline', PipelineMetricsController::class)->name('api.v1.metrics.pipeline');
    Route::apiResource('positions', PositionController::class);
});
