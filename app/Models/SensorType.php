<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorType extends Model
{
    use HasFactory;

    protected $table = 'sensor_types';

    protected $fillable = ['name'];

    // Relație: un tip de senzor poate avea mai mulți senzori de intrare
    public function inputSensors()
    {
        return $this->hasMany(InputSensor::class);
    }
}
