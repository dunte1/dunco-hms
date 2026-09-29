<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hei_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newborn_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('mother_patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('mother_art_number')->nullable();
            $table->date('birth_date');
            $table->enum('pcr_1_result', ['positive', 'negative', 'pending'])->nullable();
            $table->date('pcr_1_date')->nullable();
            $table->enum('pcr_2_result', ['positive', 'negative', 'pending'])->nullable();
            $table->date('pcr_2_date')->nullable();
            $table->enum('pcr_6_result', ['positive', 'negative', 'pending'])->nullable();
            $table->date('pcr_6_date')->nullable();
            $table->enum('final_status', ['exposed_uninfected', 'exposed_infected', 'pending'])->default('pending');
            $table->boolean('prophylaxis_given')->default(false);
            $table->date('cotrimoxazole_start')->nullable();
            $table->enum('status', ['active', 'out_of_care', 'discharged'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hei_records');
    }
};
