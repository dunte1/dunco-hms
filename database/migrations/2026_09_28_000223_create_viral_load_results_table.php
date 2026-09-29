<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('viral_load_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('care_enrollment_id')->constrained('hiv_care_enrollments')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('result_date');
            $table->unsignedBigInteger('viral_load_copies')->nullable();
            $table->string('detection_limit')->nullable();
            $table->enum('suppression_status', ['suppressed', 'unsuppressed', 'not_tested'])->default('not_tested');
            $table->foreignId('ordered_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viral_load_results');
    }
};
