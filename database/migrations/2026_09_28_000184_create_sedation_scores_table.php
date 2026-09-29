<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sedation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('icu_admission_id')->constrained('icu_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('score_type', 10)->default('rass'); // rass, sas, ramsay
            $table->integer('score_value');
            $table->dateTime('assessment_time');
            $table->text('notes')->nullable();
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sedation_scores');
    }
};
