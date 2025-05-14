<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputCommand extends Model
{
    use HasFactory;

    protected $table = 'output_commands';
    protected $fillable = ['output_sensor_id', 'command_value', 'status'];

    // Relații
    public function outputSensor()
    {
        return $this->belongsTo(OutputSensor::class);
    }
}
