<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('radiology_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('radiology_test_id')->constrained('radiology_tests')->cascadeOnDelete();
            $table->date('request_date');
            $table->date('appointment_date')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->string('status', 20)->default('pending'); // pending, scheduled, completed, cancelled
            $table->text('findings')->nullable();
            $table->text('impression')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radiology_requests');
    }
};
