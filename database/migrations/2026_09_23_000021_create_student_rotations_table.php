<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_rotations', function (Blueprint $table) {
            $table->id();
            $table->string('rotation_number', 30)->unique();
            $table->string('student_name', 200);
            $table->string('student_email')->nullable();
            $table->string('student_phone', 20)->nullable();
            $table->string('institution', 200)->nullable();
            $table->string('program', 100)->nullable(); // e.g. MBChB, Nursing, Clinical Medicine
            $table->foreignId('department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 30)->default('scheduled'); // scheduled, active, completed, cancelled
            $table->text('objectives')->nullable();
            $table->text('evaluation')->nullable();
            $table->integer('performance_score')->nullable(); // 1-100
            $table->string('performance_rating', 30)->nullable(); // excellent, good, satisfactory, needs_improvement, poor
            $table->text('supervisor_comments')->nullable();
            $table->text('student_feedback')->nullable();
            $table->timestamp('evaluation_date')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_rotations');
    }
};
