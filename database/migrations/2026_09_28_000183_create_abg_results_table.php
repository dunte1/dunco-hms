<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('abg_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('icu_admission_id')->constrained('icu_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('ph', 4, 2);
            $table->decimal('pco2', 5, 1);
            $table->decimal('po2', 5, 1);
            $table->decimal('hco3', 5, 1);
            $table->decimal('be', 5, 1);
            $table->decimal('sao2', 5, 1);
            $table->decimal('lactate', 4, 1)->nullable();
            $table->text('interpretation')->nullable();
            $table->dateTime('collected_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abg_results');
    }
};
