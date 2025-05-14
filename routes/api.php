<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataController;

Route::post('/data', [DataController::class, 'store']);



