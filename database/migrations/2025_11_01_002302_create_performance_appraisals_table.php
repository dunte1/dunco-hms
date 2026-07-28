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
        Schema::create('performance_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('appraised_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Appraisal period
            $table->string('review_period'); // e.g., "Q1 2025", "Annual 2025", "Jan-Mar 2025"
            $table->date('appraisal_date');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            
            // Overall rating
            $table->decimal('overall_score', 5, 2)->nullable(); // 0.00 to 100.00
            $table->string('overall_rating')->nullable(); // excellent, good, satisfactory, needs_improvement, poor
            
            // Detailed ratings - stored as JSON for flexibility
            $table->json('skill_ratings')->nullable(); // technical skills
            $table->json('behavioral_ratings')->nullable(); // teamwork, communication, etc.
            $table->json('kpi_ratings')->nullable(); // key performance indicators
            
            // Strengths, areas for improvement, goals
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals_for_next_period')->nullable();
            
            // Comments
            $table->text('employee_comments')->nullable();
            $table->text('appraiser_comments')->nullable();
            $table->text('hr_comments')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'archived'])->default('draft');
            
            // Dates
            $table->date('submitted_at')->nullable();
            $table->date('reviewed_at')->nullable();
            $table->date('approved_at')->nullable();
            
            // Promotion recommendation
            $table->boolean('promotion_recommended')->default(false);
            $table->text('promotion_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('employee_id');
            $table->index('appraisal_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_appraisals');
    }
};
