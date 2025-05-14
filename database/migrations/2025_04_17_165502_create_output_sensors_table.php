<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('output_sensors', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->string('sensor_type'); // ex: 'relay'
            $table->string('sensor_name');
            $table->integer('pin_number')->nullable();
            $table->json('parameters')->nullable(); // ex: {"default_state": 0}
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('output_sensors');
    }
};
