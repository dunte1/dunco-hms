<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->string('case_number')->unique();
            $table->date('diagnosis_date');
            $table->string('specimen_type', 30);
            $table->string('test_method', 30);
            $table->string('test_result', 20);
            $table->boolean('pulmonary')->default(true);
            $table->boolean('drug_susceptible')->default(true);
            $table->boolean('mdr_tb')->default(false);
            $table->string('status', 30)->default('active');
            $table->date('treatment_start_date')->nullable();
            $table->date('treatment_end_date')->nullable();
            $table->date('outcome_date')->nullable();
            $table->string('outcome')->nullable();
            $table->foreignId('registered_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_cases');
    }
};
