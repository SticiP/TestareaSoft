<?php

use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataController;

Route::post('/data/old', [DataController::class, 'store_old']);
Route::get('/data', [DataController::class, 'store']);
Route::get('/realtime/data', [ChartController::class, 'realtimeData']);



