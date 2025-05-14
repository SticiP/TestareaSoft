<?php

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function configureRateLimiting(): void
    {
        RateLimiter::for('device-api', function (Request $request) {
            $mac = $request->input('mac_address') ?? $request->ip();

            return Limit::perMinute(30)->by($mac);
        });
    }

}
