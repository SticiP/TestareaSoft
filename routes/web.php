<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InputSensorsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function ()
{
    Route::get('/login', [UserController::class, 'login_page'])->name('login.page');
    Route::get('/register', [UserController::class, 'register_page'])->name('register.page');

    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

Route::middleware('user')->group(function ()
{
    Route::get('/', [ViewController::class, 'index'])->name('home');

    Route::view('/test', 'test');

    Route::get('/device/add', [DeviceController::class, 'add_page'])->name('device.add');
    Route::post('/device/add', [DeviceController::class, 'store'])->name('device.store');

    Route::get('/device/get', [DeviceController::class, 'get'])->name('device.get');
    Route::get('/device/{id}', [DeviceController::class, 'device_page'])->name('device.page');

    Route::get('/sensors/get', [InputSensorsController::class, 'get'])->name('sensors.get');
    Route::get('/sensors/type', [InputSensorsController::class, 'get_type'])->name('sensors.type');

    Route::get('/sensors/type/add', [InputSensorsController::class, 'sensor_type_add'])->name('sensor.type.add');
    Route::post('/sensors/type/store', [InputSensorsController::class, 'sensor_type_store'])->name('sensor.type.store');
//    Route::get('/get/data', [InputSensorsController::class, 'data'])->name('sensors.data');

    Route::post('/chart/add', [ChartController::class, 'store'])->name('chart.store');
    Route::post('/chart/remove', [ChartController::class, 'remove'])->name('chart.remove');

    Route::get('/sensor/{id}', [InputSensorsController::class, 'sensor_page'])->name('sensor.page');

    Route::get('/change/status/{deviceName}', [DeviceController::class, 'changeStatus'])->name('change.status');
});

Route::post('/logout', [UserController::class, 'logout'])->name('logout');


