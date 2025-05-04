<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PatientFileController;

Route::middleware('auth.basic')->group(function () {
    Route::apiResource('patients', PatientController::class)
        ->only(['index']);
    Route::apiResource('patients.files', PatientFileController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->shallow();
});

