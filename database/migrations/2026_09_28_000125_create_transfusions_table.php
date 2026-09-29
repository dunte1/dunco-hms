<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_issue_id')->constrained('blood_issues')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('blood_unit_id')->constrained('blood_units')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('volume_transfused_ml')->nullable();
            $table->foreignId('performed_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['in_progress', 'completed', 'stopped'])->default('in_progress');
            $table->text('stop_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfusions');
    }
};
