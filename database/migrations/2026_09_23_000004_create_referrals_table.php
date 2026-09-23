<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('opd_visit_id')->nullable()->constrained('opd_visits')->nullOnDelete();
            $table->foreignId('ipd_admission_id')->nullable()->constrained('ipd_admissions')->nullOnDelete();
            $table->string('referral_number', 30)->unique();
            $table->enum('referral_type', ['in', 'out'])->default('out');
            $table->string('referring_facility', 200)->nullable();
            $table->string('receiving_facility', 200)->nullable();
            $table->foreignId('referring_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('receiving_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->text('clinical_summary')->nullable();
            $table->text('investigations_done')->nullable();
            $table->string('urgency', 30)->default('routine'); // emergency, urgent, routine
            $table->string('status', 30)->default('pending'); // pending, accepted, completed, rejected
            $table->timestamp('referred_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('outcome')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
