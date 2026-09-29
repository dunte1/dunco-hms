<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nicu_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newborn_id')->constrained('newborns')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->dateTime('admission_date');
            $table->text('reason');
            $table->integer('admission_weight_grams');
            $table->enum('status', ['active', 'discharged', 'transferred'])->default('active');
            $table->dateTime('discharge_date')->nullable();
            $table->integer('discharge_weight_grams')->nullable();
            $table->string('discharge_destination')->nullable();
            $table->foreignId('admitted_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nicu_admissions');
    }
};
