<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('input_sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('input_sensor_id')->constrained('input_sensors')->onDelete('cascade');
            $table->float('value');
            $table->timestamps();

            $table->index('created_at'); // doar asta e ok aici
        });

    }

    public function down()
    {
        Schema::dropIfExists('input_sensor_data');
    }
};
