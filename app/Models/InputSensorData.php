<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputSensorData extends Model
{
    use HasFactory;

    protected $table = 'input_sensor_data';
    protected $fillable = ['input_sensor_id', 'value'];

    // Relații
    public function inputSensor()
    {
        return $this->belongsTo(InputSensor::class);
    }
}
