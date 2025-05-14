<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index()
    {
        $devices = collect(); // Inițializează ca colecție goală

        // Verifică autentificarea
        if (auth()->check()) {
            $devices = auth()->user()->devices()->get();
        }

        $configs = Chart::where('user_id', auth()->id())->get();

        return view('index', compact('devices', 'configs'));
    }
}
