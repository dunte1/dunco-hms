<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('operation_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('operation_name');
            $table->text('operation_description');
            $table->date('operation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes');
            $table->foreignId('surgeon_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('assistant_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('anesthesiologist_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('nurses')->nullOnDelete();
            $table->string('anesthesia_type', 50)->nullable();
            $table->text('pre_operation_notes')->nullable();
            $table->text('operation_notes');
            $table->text('post_operation_notes')->nullable();
            $table->text('complications')->nullable();
            $table->string('outcome', 50); // successful, complications, unsuccessful
            $table->text('follow_up_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_reports');
    }
};
