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
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email');
                $table->string('phone');
                $table->text('cover_letter')->nullable();
                $table->string('resume_path')->nullable();
                $table->string('status', 20)->default('pending'); // pending, shortlisted, rejected, hired
                $table->date('interview_date')->nullable();
                $table->text('interview_notes')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete(); // If converted to employee
                $table->timestamps();
            });
        } else {
            // Table exists, add missing columns if needed
            Schema::table('job_applications', function (Blueprint $table) {
                if (!Schema::hasColumn('job_applications', 'interview_date')) {
                    $table->date('interview_date')->nullable();
                }
                if (!Schema::hasColumn('job_applications', 'interview_notes')) {
                    $table->text('interview_notes')->nullable();
                }
                if (!Schema::hasColumn('job_applications', 'reviewed_by')) {
                    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('job_applications', 'reviewed_at')) {
                    $table->timestamp('reviewed_at')->nullable();
                }
                if (!Schema::hasColumn('job_applications', 'employee_id')) {
                    $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
