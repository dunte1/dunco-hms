<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ventilator_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('icu_admission_id')->constrained('icu_admissions')->cascadeOnDelete();
            $table->string('mode', 20)->default('volume'); // volume, pressure, simv, bipap, cpap, niv
            $table->integer('set_rate')->nullable();
            $table->integer('tidal_volume')->nullable();
            $table->integer('peep')->nullable();
            $table->integer('fio2')->nullable();
            $table->integer('pressure_support')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('status', 20)->default('active'); // active, stopped
            $table->text('reason_for_change')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventilator_settings');
    }
};
