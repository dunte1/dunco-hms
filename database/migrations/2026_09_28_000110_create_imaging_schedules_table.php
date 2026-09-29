<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imaging_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_request_id')->constrained('radiology_requests')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('radiology_test_id')->constrained('radiology_tests')->cascadeOnDelete();
            $table->string('modality', 20); // xray, ultrasound, ct, mri, mammography, fluoroscopy
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->string('status', 20)->default('scheduled'); // scheduled, completed, cancelled
            $table->foreignId('scheduled_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imaging_schedules');
    }
};
