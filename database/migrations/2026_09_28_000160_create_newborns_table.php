<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('newborns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('birth_report_id')->nullable()->constrained('birth_reports')->nullOnDelete();
            $table->foreignId('mother_patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('baby_name')->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->date('date_of_birth');
            $table->time('time_of_birth');
            $table->integer('birth_weight_grams');
            $table->decimal('gestational_age_weeks', 4, 1);
            $table->tinyInteger('apgar_1_min');
            $table->tinyInteger('apgar_5_min');
            $table->tinyInteger('apgar_10_min')->nullable();
            $table->enum('status', ['well_baby', 'nicu', 'discharged', 'deceased'])->default('well_baby');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newborns');
    }
};
