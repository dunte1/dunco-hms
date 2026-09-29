<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_handover_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('ambulance_trips')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('receiving_facility')->nullable();
            $table->string('receiving_person')->nullable();
            $table->string('receiving_department')->nullable();
            $table->text('clinical_summary');
            $table->datetime('handover_time');
            $table->foreignId('handed_over_by')->constrained('users')->cascadeOnDelete();
            $table->string('received_by_name')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('status', 20)->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_handover_records');
    }
};
