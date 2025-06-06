<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use App\Models\Device;
use App\Models\InputSensor;
use App\Models\InputSensorData;
use App\Models\SensorType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    private function generateLabels($dateFrom, $dateTo)
    {
        $labels = [];
        $start = new \DateTime($dateFrom);
        $end = new \DateTime($dateTo);

        $totalDuration = $end->getTimestamp() - $start->getTimestamp();
        $intervalDuration = max(1, $totalDuration / 20);

        for ($i = 0; $i < 20; $i++) {
            $timestamp = $start->getTimestamp() + $i * $intervalDuration;
            $labels[] = date('Y-m-d H:i', $timestamp);
        }

//        $interval = new \DateInterval('P1D'); // 1 zi
//        $period = new \DatePeriod($start, $interval, $end->modify('+1 day'));
//
//        foreach ($period as $date) {
//            $labels[] = $date->format('Y-m-d');
//        }

        return $labels;
    }

    private function generateDatasets($sensors, $dateFrom, $dateTo, $type, $labels)
    {
        $colors = [
            '#6571ff', // primary
            '#ff3366', // danger
            '#fbbc06', // warning
            '#05a34a', // success
            '#66d1d1', // info
        ];

        $datasets = [];
        foreach ($sensors as $index => $sensorId) {
            // Presupunem că ai o metodă de a prelua datele măsurate pentru senzor
            $sensor = InputSensor::find($sensorId);
            $measurements = $this->getSensorMeasurements($sensorId, $dateFrom, $dateTo, $labels);
            $datasets[] = [
                'label' => $sensor->sensor_name ?? 'Sensor ' . ($index + 1),
                'backgroundColor' => $colors[$index % count($colors)],
                'borderColor' => $colors[$index % count($colors)],
                'data' => $measurements,
                'fill' =>$type === 'line' ? false : true, // Umplere pentru linie
                'tension' => $type === 'line' ? 0.3 : null, // Tensiune pentru linie
            ];
        }

        return $datasets;
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function getSensorMeasurements($sensorId, $dateFrom, $dateTo, $labels)
    {
        $sensor = InputSensor::where('id', $sensorId)
            ->with([
                'data' => function($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('created_at', [$dateFrom, $dateTo]);
                },
                'sensorType'
            ])
            ->firstOrFail();

        $allData = $sensor->data()
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at')
            ->get();

        $valuesArray = $allData->map(function($item) {
            return [
                'value' => $item->value,
                'created_at' => $item->created_at
            ];
        })->toArray();

        if (empty($valuesArray) || empty($labels)) {
            return array_fill(0, count($labels), 0);
        }

        // Preluăm prima și ultima dată din setul de date
        $firstDate = $valuesArray[0]['created_at'];
        $lastDate = last($valuesArray)['created_at'];

        $result = [];

        $currentLabel = Carbon::parse($labels[15]);
        foreach ($labels as $label) {
            $currentLabel = Carbon::parse($label);

            // 1. Cazul când label-ul e înainte de primele date
            if ($currentLabel->lt($firstDate)) {
                $result[] = 0;
                continue;
            }

            // 2. Cazul când label-ul e după ultimele date
            if ($currentLabel->gt($lastDate)) {
                $result[] = 0;
                continue;
            }

            $closest = 0;
            $minDiff = PHP_INT_MAX;

            foreach ($valuesArray as $data)
            {
                $diff = abs($data['created_at']->diffInSeconds($currentLabel));

                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $closest = $data['value'];
                }

                // Optimizare: Oprește căutarea dacă am depășit data label-ului
                if ($data['created_at']->gt($currentLabel)) {
                    break;
                }
            }

            $result[] = $closest;
        }

        return $result;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lib' => 'required|string',
            'type' => 'required|string',
            'devices' => 'required|array',
            'devices.*' => 'exists:devices,id',
//            'sensor_type' => 'exists:sensor_types,id',
            'sensors' => 'required|array',
//            'sensors.*' => 'exists:input_sensors,id',
            'date_from' => 'required|date_format:Y-m-d H:i', // Specificăm formatul
            'date_to' => [
                'required_unless:current_time,on', // Nu este necesar dacă current_time este activat
                'date_format:Y-m-d H:i',
                'after_or_equal:date_from'
            ],
            'current_time' => 'nullable|in:on'
        ]);

        if ($request->has('current_time') && $request->current_time === 'on') {
            $validated['date_to'] = now()->format('Y-m-d H:i');
        }

        $devices_id = $validated['devices'];
        $sensor = $validated['sensors'][0];
//        $sensors = $validated['sensors'];
//        $sensorTypeId = $validated['sensor_type'];
        $dateFrom = $validated['date_from'];
        $dateTo = $validated['date_to'];
        $type = $validated['type'];
        $lib = $validated['lib'];

        $devices = Device::whereIn('id', $devices_id)->get();
        $title = $devices->pluck('device_name')->implode(' / ');

        $labels = $this->generateLabels($dateFrom, $dateTo);

        $sensors = InputSensor::whereHas('device', function ($query) use ($devices) {
            $query->whereIn('id', $devices->pluck('id'));
        })
            ->where('sensor_name', $sensor)
            ->pluck('id')
            ->toArray();

        $datasets = $this->generateDatasets($sensors, $dateFrom, $dateTo, $type, $labels);

        // Construim configurația Chart.js
        $chartData = [
            'type' => $type,
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['display' => true],
                ],
                'scales' => [
                    'x' => [
                        'display' => true,
                        'grid' => [
                            'display' => true,
                            'color' => 'rgba(77, 138, 240, .15)',
                            'borderColor' => 'rgba(77, 138, 240, .15)',
                        ],
                        'ticks' => [
                            'color' => '#b8c3d9',
                            'font' => ['size' => 12]
                        ]
                    ],
                    'y' => [
                        'grid' => [
                            'display' => true,
                            'color' => 'rgba(77, 138, 240, .15)',
                            'borderColor' => 'rgba(77, 138, 240, .15)',
                        ],
                        'ticks' => [
                            'color' => '#b8c3d9',
                            'font' => ['size' => 12]
                        ]
                    ]
                ]
            ]
        ];

        // Salvăm configurația în baza de date
        Chart::create([
            'user_id' => auth()->id(),
            'lib' => $lib,
            'type' => $type,
            'data' => json_encode($chartData),
            'title' => $title . " - " . $sensor ?? ' Chart',
        ]);

        // Redirecționează cu un mesaj de succes
        return redirect()->back()->with('success', 'Configurația graficului a fost salvată!');
    }

    public function remove(Request $request)
    {
        $chartId = $request->input('chart_id');
        $chart = Chart::where('id', $chartId)->where('user_id', auth()->id())->first();

        if ($chart) {
            $chart->delete();
            return redirect()->back()->with('success', 'Graficul a fost șters cu succes!');
        } else {
            return redirect()->back()->with('error', 'Graficul nu a fost găsit sau nu aveți permisiunea de a-l șterge.');
        }
    }

    /**
     * Returnează ultimele N puncte pentru sensorul dat.
     */
    public function realtimeData(Request $request)
    {
        $sensorId = $request->query('sensor_id');
        $limit    = $request->query('limit', 50);

        // Exemplu: tabel `measurements` cu coloane `sensor_id`, `value`, `created_at`
        $rows = InputSensorData::where('input_sensor_id', $sensorId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['created_at', 'value']);

        // Flot aşteaptă un array de [ [timestamp_ms, value], ... ], ordonat ascendent
        $data = $rows
            ->map(function($r){
                // convertim Carbon->milisecunde
                $ts = strtotime($r->created_at) * 1000;
                return [ $ts, (float) $r->value ];
            })
            ->reverse()  // ca să fie ordonate cronologic
            ->values();

        return response()->json($data);
    }
}
