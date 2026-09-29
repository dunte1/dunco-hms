<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('child_protection_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('case_number')->unique();
            $table->enum('concern_type', ['neglect', 'physical', 'sexual', 'emotional', 'other']);
            $table->text('description');
            $table->enum('risk_level', ['low', 'moderate', 'high', 'critical'])->default('moderate');
            $table->enum('status', ['open', 'investigation', 'confirmed', 'closed', 'referred'])->default('open');
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->date('reported_date');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('referral_agency')->nullable();
            $table->text('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_protection_cases');
    }
};
