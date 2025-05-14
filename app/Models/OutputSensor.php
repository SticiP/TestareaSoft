<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputSensor extends Model
{
    use HasFactory;

    protected $table = 'output_sensors';

    protected $fillable = [
        'device_id',
        'sensor_type_id', // înlocuit 'sensor_type'
        'sensor_name',
        'pin_number',
        'parameters',
    ];

    // Relații
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function commands()
    {
        return $this->hasMany(OutputCommand::class);
    }

    public function sensorType()
    {
        return $this->belongsTo(SensorType::class);
    }
}
