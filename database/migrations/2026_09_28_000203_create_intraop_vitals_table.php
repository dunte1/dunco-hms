<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('intraop_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anaesthesia_record_id')->constrained('anaesthesia_records')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamp('time_recorded')->nullable();
            $table->integer('heart_rate')->unsigned()->nullable();
            $table->integer('blood_pressure_sys')->unsigned()->nullable();
            $table->integer('blood_pressure_dia')->unsigned()->nullable();
            $table->integer('map')->unsigned()->nullable();
            $table->decimal('spo2', 5, 2)->nullable();
            $table->decimal('etco2', 5, 2)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('respiratory_rate')->unsigned()->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intraop_vitals');
    }
};
