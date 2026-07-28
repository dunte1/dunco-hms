<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_program_id')->constrained('training_programs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('status', ['registered', 'attended', 'completed', 'absent', 'cancelled'])->default('registered');
            $table->integer('attendance_hours')->default(0);
            $table->boolean('certificate_issued')->default(false);
            $table->string('certificate_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['training_program_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_enrollments');
    }
};
