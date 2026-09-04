<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Biometric data storage
        if (!Schema::hasTable('biometric_data')) {
            Schema::create('biometric_data', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('biometric_type');
                $table->string('template_hash')->unique();
                $table->text('encrypted_template');
                $table->string('device_info')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->index(['user_id', 'biometric_type', 'is_active']);
            });
        }

        // Biometric verification logs
        if (!Schema::hasTable('biometric_verification_logs')) {
            Schema::create('biometric_verification_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('biometric_type');
                $table->boolean('success');
                $table->decimal('confidence_score', 5, 2);
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->index(['user_id', 'created_at']);
                $table->index(['success', 'created_at']);
            });
        }

        // Card scanner logs
        if (!Schema::hasTable('card_scan_logs')) {
            Schema::create('card_scan_logs', function (Blueprint $table) {
                $table->id();
                $table->string('card_type');
                $table->string('card_number')->nullable();
                $table->string('scanned_data')->nullable();
                $table->string('scanner_location')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('patient_id')->nullable();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('metadata')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('patient_id')->references('id')->on('patients')->nullOnDelete();
                $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
                $table->index(['card_type', 'created_at']);
                $table->index(['patient_id', 'created_at']);
                $table->index(['employee_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('card_scan_logs');
        Schema::dropIfExists('biometric_verification_logs');
        Schema::dropIfExists('biometric_data');
    }
};

