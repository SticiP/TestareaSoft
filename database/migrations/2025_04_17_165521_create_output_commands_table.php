<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('output_commands', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('output_sensor_id')->constrained('output_sensors')->onDelete('cascade');
            $table->float('command_value'); // ex: 1/0 pentru releu
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('output_commands');
    }
};
