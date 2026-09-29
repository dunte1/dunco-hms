<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hiv_care_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('hts_encounter_id')->nullable()->constrained('hts_encounters')->nullOnDelete();
            $table->date('enrollment_date');
            $table->string('art_number')->nullable();
            $table->tinyInteger('who_stage')->unsigned()->nullable();
            $table->integer('baseline_cd4')->unsigned()->nullable();
            $table->integer('baseline_viral_load')->unsigned()->nullable();
            $table->foreignId('enrolled_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['active', 'transferred_out', 'discontinued', 'deceased'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiv_care_enrollments');
    }
};
