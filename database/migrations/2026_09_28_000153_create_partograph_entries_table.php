<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partograph_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('labour_record_id')->constrained('labour_records')->cascadeOnDelete();
            $table->dateTime('time_recorded');
            $table->integer('cervical_dilation')->nullable();
            $table->integer('descent')->nullable();
            $table->integer('contractions_per_10')->nullable();
            $table->integer('fetal_heart_rate')->nullable();
            $table->string('liquor', 20)->nullable();
            $table->string('moulding', 20)->nullable();
            $table->integer('maternal_pulse')->nullable();
            $table->string('maternal_bp', 20)->nullable();
            $table->integer('urine_output')->nullable();
            $table->decimal('oxytocin_dose', 6, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partograph_entries');
    }
};
