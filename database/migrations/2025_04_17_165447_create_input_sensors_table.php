<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('input_sensors', function (Blueprint $table) {
            $table->id(); // Asta generează coloana "id" cu auto increment
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
//            $table->foreignId('sensor_type_id')->constrained('sensor_types')->onDelete('restrict');
            $table->string('sensor_name');
            $table->timestamps();

            $table->unique(['device_id', 'sensor_name'], 'input_sensors_unique');

            // Indexuri utile:
//            $table->index(['device_id', 'pin_number']);
//            $table->index('sensor_name');
//            $table->index('created_at');
//
            // Unique compus pentru upsert
//            $table->unique(['device_id', 'sensor_name', 'pin_number'], 'device_sensor_pin_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('input_sensors');
    }
};
