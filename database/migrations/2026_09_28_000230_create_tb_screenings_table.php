<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->date('screening_date');
            $table->boolean('symptoms_cough')->default(false);
            $table->boolean('symptoms_fever')->default(false);
            $table->boolean('symptoms_night_sweats')->default(false);
            $table->boolean('symptoms_weight_loss')->default(false);
            $table->text('symptoms_other')->nullable();
            $table->boolean('contact_history')->default(false);
            $table->string('hiv_status', 20)->default('unknown');
            $table->string('chest_xray_result')->nullable();
            $table->string('screen_result', 20);
            $table->foreignId('screened_by')->constrained('users');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_screenings');
    }
};
