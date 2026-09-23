<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->string('internship_number', 30)->unique();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('intern_name', 200);
            $table->string('intern_email')->nullable();
            $table->string('intern_phone', 20)->nullable();
            $table->string('institution', 200)->nullable();
            $table->string('program', 100)->nullable();
            $table->foreignId('department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 30)->default('active'); // active, completed, terminated
            $table->text('description')->nullable();
            $table->text('supervisor_notes')->nullable();
            $table->text('evaluation')->nullable();
            $table->integer('performance_score')->nullable();
            $table->string('performance_rating', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
