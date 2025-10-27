<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataExportController;

Route::get('/export-data', [DataExportController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function ($request) {
    return $request->user();
});
