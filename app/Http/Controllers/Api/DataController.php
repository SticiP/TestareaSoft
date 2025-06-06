<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSensorDataRequest;
use App\Models\Device;
use App\Models\InputSensor;
use App\Models\OutputCommand;
use App\Models\OutputSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function store_old(StoreSensorDataRequest $request)
    {
        $device = Device::where('mac_address', $request->mac_address)->firstOrFail();
        $deviceId = $device->id;
        $now = now();

        $sensorRows = [];
        foreach ($request->sensors as $sensor) {
            $sensorRows[] = [
                'device_id'   => $deviceId,
                'sensor_name' => $sensor['sensor_name'],
                'pin_number'  => $sensor['pin_number'],
                'sensor_type' => $sensor['sensor_type'],
                'parameters'  => json_encode($sensor['parameters'] ?? []),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        $dataRows = [];

        DB::transaction(function () use ($sensorRows, $request, $deviceId, $now, &$dataRows) {
            // Bulk upsert pentru input_sensors
            collect($sensorRows)->chunk(500)->each(function ($chunk) {
                InputSensor::upsert(
                    $chunk->toArray(),
                    ['device_id', 'sensor_name', 'pin_number'], // cheie unică
                    ['sensor_type', 'parameters', 'updated_at'] // coloane de actualizat
                );
            });

            // Obține senzorii actuali (după upsert) pentru a extrage id‑urile
            $sensorKeys = collect($sensorRows)->map(fn($s) => [
                'sensor_name' => $s['sensor_name'],
                'pin_number'  => $s['pin_number'],
            ]);

            $existingSensors = InputSensor::where('device_id', $deviceId)
                ->whereIn('sensor_name', $sensorKeys->pluck('sensor_name'))
                ->whereIn('pin_number', $sensorKeys->pluck('pin_number'))
                ->get(['id', 'sensor_name', 'pin_number']);

            // Construiește valorile pentru input_sensor_data
            foreach ($request->sensors as $sensor) {
                $matched = $existingSensors->first(fn($s) =>
                    $s->sensor_name === $sensor['sensor_name'] &&
                    $s->pin_number === $sensor['pin_number']
                );

                if ($matched) {
                    $dataRows[] = [
                        'input_sensor_id' => $matched->id,
                        'value'           => $sensor['value'],
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
            }

            // Bulk insert în input_sensor_data
            collect($dataRows)->chunk(500)->each(function ($chunk) {
                DB::table('input_sensor_data')->insert($chunk->toArray());
            });
        });

        return response()->json([
            'message' => 'Data received successfully',
            'device' => $device->device_name,
            'sensors_processed' => count($dataRows),
        ], 201);
    }

    public function store(Request $request)
    {
        $data = $request->query();

        if (empty($data)) {
            return response()->json(['message' => 'No data provided'], 400);
        }

        $mac = $data['a0'];
        unset($data['a0']);

        $device = Device::where('mac_address', $mac)->first();

        if(!$device)
        {
            return response()->json([
                'message' => 'No Device Found',
            ], 202);
        }

        $sensorNames = array_keys($data);

        $sensors = InputSensor::whereIn('sensor_name', $sensorNames)
            ->get()
            ->keyBy('sensor_name');

        $now = now();
        $rows = [];

        foreach ($data as $name => $value) {
            if (! $sensors->has($name)) {
                continue;
            }
            $rows[] = [
                'input_sensor_id' => $sensors[$name]->id,
                'value'           => (float) $value,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        // 3) Bulk insert
        if (! empty($rows)) {
            DB::table('input_sensor_data')->insert($rows);
        }

        $sensorName = 'd2';

        if ($device) {
            $outputCommand = OutputCommand::whereHas('outputSensor', function ($query) use ($sensorName, $device) {
                $query->where('sensor_name', $sensorName)
                    ->where('device_id', $device->id);
            })->latest()->first();

            if ($outputCommand) {
                $output = $outputCommand->command_value;
            } else {
                $output = 0;
            }
        }

        return response("d2=" . $output, 201)
            ->header('Content-Type', 'text/plain');
    }
}
