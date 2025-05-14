<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';
    protected $fillable = ['mac_address', 'device_name', 'user_id'];

    // Relații
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inputSensors()
    {
        return $this->hasMany(InputSensor::class);
    }

    public function outputSensors()
    {
        return $this->hasMany(OutputSensor::class);
    }
}
