<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('complainant_name')->nullable();
            $table->string('complainant_phone')->nullable();
            $table->string('complainant_email')->nullable();
            $table->string('department')->nullable();
            $table->string('complaint_type', 50); // service_quality, waiting_time, billing, staff_conduct, facility, other
            $table->text('description');
            $table->string('status', 20)->default('received'); // received, acknowledged, investigating, resolved, closed
            $table->string('priority', 10)->default('medium'); // low, medium, high
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->constrained('users');
            $table->datetime('received_at');
            $table->datetime('acknowledged_at')->nullable();
            $table->datetime('resolved_at')->nullable();
            $table->text('resolution')->nullable();
            $table->integer('satisfaction_score')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
