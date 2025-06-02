<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('output_sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
//            $table->foreignId('sensor_type_id')->constrained('sensor_types')->onDelete('restrict');
            $table->string('sensor_name');
//            $table->integer('pin_number')->nullable();
//            $table->json('parameters')->nullable(); // ex: {"default_state": 0}
            $table->timestamps();

            $table->unique(['device_id', 'sensor_name'], 'output_sensors_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('output_sensors');
    }
};
