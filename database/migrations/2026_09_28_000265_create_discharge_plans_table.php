<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discharge_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->unsignedBigInteger('ipd_admission_id')->nullable();
            $table->date('discharge_date')->nullable();
            $table->text('home_care_needs');
            $table->text('follow_up_appointments');
            $table->text('equipment_needs')->nullable();
            $table->text('community_services')->nullable();
            $table->text('caregiver_involvement')->nullable();
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discharge_plans');
    }
};
