<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InputSensor;
use App\Models\InputSensorData;
use App\Models\SensorType;
use Illuminate\Http\Request;

class InputSensorsController extends Controller
{
    public function get_type(Request $request)
    {
        $deviceIds = $request->input('device_ids', []);

        $devices = auth()->user()->devices()
            ->whereIn('id', $deviceIds)
            ->pluck('id')
            ->toArray();

        // Extragem DISTINCT sensor_type_id din input_sensors pentru aceste device-uri
        $sensorTypeIds = InputSensor::whereIn('device_id', $devices)
            ->whereNotNull('sensor_type_id')
            ->distinct()
            ->pluck('sensor_type_id')
            ->toArray();

        // Luăm numele tipurilor de senzori din tabela sensor_types
        $sensorTypes = SensorType::whereIn('id', $sensorTypeIds)
            ->pluck('name')
            ->filter()
            ->values();

        return response()->json($sensorTypes);
    }

    public function get(Request $request)
    {
        $deviceIds = $request->input('device_ids', []);
        $typeId = $request->input('type_id');

        $devices = auth()->user()->devices()
            ->whereIn('id', $deviceIds)
            ->pluck('id')
            ->toArray();

        $query = InputSensor::whereIn('device_id', $devices);

        if ($typeId) {
            $query->where('sensor_type_id', $typeId);
        }

        $sensors = $query->get(['id', 'sensor_name']);


        return response()->json($sensors);
    }

    public function data(Request $request)
    {
        $deviceIds = $request->input('devices', []);
        $sensorIds = $request->input('sensors', []);
        $type = $request->input('type'); // tipul graficului
        $lib = $request->input('lib');
        $from = $request->input('date_from');
        $to = $request->input('date_to');

        // Doar senzorii care aparțin utilizatorului
        $userSensorIds = InputSensor::whereIn('device_id', auth()->user()->devices()->pluck('id'))
            ->whereIn('id', $sensorIds)
            ->pluck('id');

        // Extragem datele
        $data = InputSensorData::whereIn('input_sensor_id', $userSensorIds)
            ->when($from, fn($q) => $q->where('created_at', '>=', $from))
            ->when($to,   fn($q) => $q->where('created_at', '<=', $to))
            ->orderBy('created_at')
            ->get();

        // Grupăm datele după sensor
        $grouped = $data->groupBy('input_sensor_id');

        $sensors = InputSensor::whereIn('id', $grouped->keys()->all())->get()->keyBy('id');

        $datasets = $grouped->map(function ($records, $sensorId) use ($sensors) {
            $sensorName = $sensors[$sensorId]->sensor_name ?? "Senzor #{$sensorId}";
            return [
                'label'       => $sensorName,
                'data'        => $records->map(fn($r) => [
                    'x' => $r->created_at->toIso8601String(),
                    'y' => $r->value,
                ]),
                'fill'        => false,
                'borderColor' => '#' . substr(md5($sensorId), 0, 6),
            ];
        })->values();

        $title = $sensors->first()->sensor_type;

        $response = [
            'title' => $sensors->first()->sensor_type,
            'lib' => $lib,
            'options' => [],
        ];

        // Configurăm opțiunile pentru fiecare librărie
        if ($lib === 'chartjs') {
            $response['options']['chartjs'] = [
                'type' => $type ?? 'line',
                'data' => [
                    'datasets' => $datasets,
                ],
                'options' => [
                    'responsive' => true,
                    'scales' => [
                        'x' => [
                            'type' => 'time',
                            'time' => ['unit' => 'minute']
                        ],
                        'y' => [
                            'beginAtZero' => true
                        ]
                    ]
                ]
            ];
        }

        // TODO: opțiuni pentru ApexCharts și Flot dacă e nevoie

        return response()->json($response);
    }
}
