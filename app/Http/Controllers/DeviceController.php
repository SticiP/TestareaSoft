<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InputSensor;
use App\Models\OutputCommand;
use App\Models\OutputSensor;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // Arată formularul de creare
    public function add_page()
    {
//        $sensor_types = SensorType::all();
//        return view('add_device', compact('sensor_types'));
        return view('add_device');
    }

    // Salvează dispozitivul în baza de date
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name'    => 'required|string|max:255',
            'mac_address'    => 'required|string|unique:devices,mac_address',
//            'input_sensors'  => 'nullable|array',
//            'input_sensors.*.sensor_type_id' => 'required|integer|exists:sensor_types,id',
//            'input_sensors.*.sensor_name'    => 'required|string|max:255|unique:input_sensors,sensor_name',
//            'input_sensors.*.pin_number'     => 'required|integer|min:0',
//            'input_sensors.*.is_output'      => 'nullable|in:on',
        ]);

        $device = auth()->user()->devices()->create([
            'device_name' => $validated['device_name'],
            'mac_address' => $validated['mac_address'],
        ]);

        $inputSensorsNames = [
            "a1", "a2", "d1", "d2"
        ];

        $outputSensorsNames = [
            "d2"
        ];

        foreach ($inputSensorsNames as $inputSensorName) {
            InputSensor::create([
                'sensor_name' => $inputSensorName,
                'device_id' => $device->id,
            ]);
        }

        foreach ($outputSensorsNames as $outputSensorName) {
            OutputSensor::create([
                'sensor_name' => $outputSensorName,
                'device_id' => $device->id,
            ]);
        }

//        $createdInputCount = 0;
//        $createdOutputCount = 0;
//
//        foreach ($validated['input_sensors'] ?? [] as $sensorData) {
//            $sensorType = SensorType::find($sensorData['sensor_type_id']);
//
//            $unit = $sensorType->default_unit
//                ?? match($sensorType->name) {
//                    'temperature' => '°C',
//                    'humidity'    => '%',
//                    'co2'         => 'ppm',
//                    default       => null
//                };
//
//            // pregătim payload-ul pentru InputSensor
//            $inputPayload = [
//                'device_id'        => $device->id,
//                'sensor_type_id'   => $sensorData['sensor_type_id'],
//                'sensor_name'      => $sensorData['sensor_name'],
//                'pin_number'       => $sensorData['pin_number'],
//                'parameters'     => json_encode(['unit' => $unit]),
//            ];
//
//            $inputSensor = InputSensor::create($inputPayload);
//            $createdInputCount++;
//
//            // dacă a fost bifat is_output, creăm și OutputSensor
//            if (!empty($sensorData['is_output']) && $sensorData['is_output'] === 'on') {
//                OutputSensor::create([
//                    'device_id'      => $device->id,
//                    'sensor_type_id'    => $sensorData['sensor_type_id'],
//                    'sensor_name'    => $inputSensor->sensor_name,
//                    'pin_number'     => $inputSensor->pin_number,
//                    'parameters'     => json_encode(['default_state' => 0]), // sau altă logică
//                ]);
//                $createdOutputCount++;
//            }
//        }

        // 4. Răspuns JSON
        return response()->json([
            'message'               => 'Device and sensors saved successfully',
            'device_id'             => $device->id,
//            'input_sensors_count'   => $createdInputCount,
//            'output_sensors_count'  => $createdOutputCount,
        ], 201);
    }

    // Arată detaliile unui dispozitiv
    public function show(Device $device)
    {
        $output = 0;
        $sensorName = 'd2';
        if ($device) {
            $outputCommand = OutputCommand::whereHas('outputSensor', function ($query) use ($sensorName, $device) {
                $query->where('sensor_name', $sensorName)
                    ->where('device_id', $device->id);
            })->latest()->first();

            if ($outputCommand) {
                $output = $outputCommand->command_value;
            }
        }
        return view('devices.show', compact('device', 'output'));
    }
    public function get()
    {
        $devices = auth()->user()->devices()->get();

        return json_encode($devices);
    }

    public function device_page($id)
    {
        $device = auth()->user()->devices()
            ->with(['inputSensors', 'outputSensors'])
            ->findOrFail($id);

        $sensorName = 'd2';

        $outputCommand = OutputCommand::whereHas('outputSensor', function ($query) use ($sensorName, $device) {
            $query->where('sensor_name', $sensorName)
                ->where('device_id', $device->id);
        })->latest()->first();

        $output = (int) $outputCommand->command_value;

        return view('device_page', compact('device', 'output'));
    }

    public function changeStatus(Request $request, $deviceName)
    {
        // Validare simplă
        $newStatus = $request->query('status');
        $commandValue = $request->query('command_value');

        // Găsește dispozitivul după nume
        $device = Device::where('device_name', $deviceName)->firstOrFail();

        // Găsește OutputSensor potrivit (ex: cu sensor_name = 'd2')
        $outputSensor = $device->outputSensors()->where('sensor_name', 'd2')->firstOrFail();

        // Creează o nouă comandă
        $outputCommand = OutputCommand::create([
            'output_sensor_id' => $outputSensor->id,
            'command_value' => $commandValue,
            'status' => $newStatus,
        ]);

        // Returnează răspuns JSON
        return response()->json([
            'success' => true,
            'new_status' => $newStatus,
        ]);
    }
}
