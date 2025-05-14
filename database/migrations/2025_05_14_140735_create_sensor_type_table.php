<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Creăm tabela nouă pentru tipuri de senzori
        Schema::create('sensor_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ex: 'temperature', 'humidity'
            $table->timestamps();
        });

        // 2. Adăugăm coloana nouă în input_sensors
        Schema::table('input_sensors', function (Blueprint $table) {
            $table->foreignId('sensor_type_id')->nullable()->after('device_id');
        });

        // 3. Migrarea datelor existente
        $distinctTypes = DB::table('input_sensors')->select('sensor_type')->distinct()->pluck('sensor_type');

        foreach ($distinctTypes as $type) {
            DB::table('sensor_types')->insert(['name' => $type, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Setăm valorile corespunzătoare în input_sensors
        $sensors = DB::table('input_sensors')->get();
        foreach ($sensors as $sensor) {
            $typeId = DB::table('sensor_types')->where('name', $sensor->sensor_type)->value('id');
            DB::table('input_sensors')->where('id', $sensor->id)->update(['sensor_type_id' => $typeId]);
        }

        // 4. Ștergem coloana veche
        Schema::table('input_sensors', function (Blueprint $table) {
            $table->dropColumn('sensor_type');
        });

        // 5. Setăm coloana ca not null și adăugăm cheia externă
        Schema::table('input_sensors', function (Blueprint $table) {
            $table->foreign('sensor_type_id')->references('id')->on('sensor_types')->onDelete('restrict');
            $table->integer('sensor_type_id')->change(); // scoatem nullable
        });
    }

    public function down(): void
    {
        Schema::table('input_sensors', function (Blueprint $table) {
            $table->string('sensor_type')->after('device_id');
        });

        $sensors = DB::table('input_sensors')->get();
        foreach ($sensors as $sensor) {
            $typeName = DB::table('sensor_types')->where('id', $sensor->sensor_type_id)->value('name');
            DB::table('input_sensors')->where('id', $sensor->id)->update(['sensor_type' => $typeName]);
        }

        Schema::table('input_sensors', function (Blueprint $table) {
            $table->dropForeign(['sensor_type_id']);
            $table->dropColumn('sensor_type_id');
        });

        Schema::dropIfExists('sensor_types');
    }
};
