<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // Arată formularul de creare
    public function add_page()
    {
        return view('add_device');
    }

    // Salvează dispozitivul în baza de date
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'mac_address' => 'required|unique:devices'
        ]);

        auth()->user()->devices()->create($validated);

        return redirect()->route('home')->with('success', 'Dispozitiv adăugat!');
    }

    // Arată detaliile unui dispozitiv
    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    public function get()
    {
        $devices = auth()->user()->devices()->get();

        return json_encode($devices);
    }
}
