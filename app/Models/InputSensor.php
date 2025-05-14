<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputSensor extends Model
{
    use HasFactory;

    protected $table = 'input_sensors';
    protected $fillable = [
        'device_id',
        'sensor_type_id',
        'sensor_name',
        'pin_number',
        'parameters'
    ];

    // Relații
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function data()
    {
        return $this->hasMany(InputSensorData::class);
    }

    public function sensorType()
    {
        return $this->belongsTo(SensorType::class);
    }
}
