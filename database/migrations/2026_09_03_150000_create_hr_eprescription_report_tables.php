<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('e_prescription_templates')) {
            Schema::create('e_prescription_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->json('template_structure')->nullable();
                $table->json('default_fields')->nullable();
                $table->text('header_text')->nullable();
                $table->text('footer_text')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedInteger('usage_count')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('performance_appraisals')) {
            Schema::create('performance_appraisals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
                $table->foreignId('appraised_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->string('review_period')->nullable();
                $table->date('appraisal_date')->nullable();
                $table->date('period_start')->nullable();
                $table->date('period_end')->nullable();
                $table->decimal('overall_score', 5, 2)->nullable();
                $table->string('overall_rating')->nullable();
                $table->json('skill_ratings')->nullable();
                $table->json('behavioral_ratings')->nullable();
                $table->json('kpi_ratings')->nullable();
                $table->text('strengths')->nullable();
                $table->text('areas_for_improvement')->nullable();
                $table->text('goals_for_next_period')->nullable();
                $table->text('employee_comments')->nullable();
                $table->text('appraiser_comments')->nullable();
                $table->text('hr_comments')->nullable();
                $table->string('status')->default('draft');
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->boolean('promotion_recommended')->default(false);
                $table->text('promotion_notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('leave_types')) {
            Schema::create('leave_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->integer('default_days')->default(0);
                $table->boolean('carry_forward')->default(false);
                $table->boolean('requires_approval')->default(true);
                $table->string('color')->default('#6366f1');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('training_programs')) {
            Schema::create('training_programs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->integer('duration_hours')->default(0);
                $table->string('location')->nullable();
                $table->string('instructor')->nullable();
                $table->integer('max_participants')->nullable();
                $table->string('status')->default('draft');
                $table->boolean('certificate_available')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('training_enrollments')) {
            Schema::create('training_enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('training_program_id')->constrained('training_programs')->cascadeOnDelete();
                $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
                $table->string('status')->default('enrolled');
                $table->decimal('attendance_hours', 6, 2)->nullable();
                $table->boolean('certificate_issued')->default(false);
                $table->string('certificate_path')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hr_announcements')) {
            Schema::create('hr_announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('target_audience')->nullable();
                $table->unsignedBigInteger('department_id')->nullable();
                $table->unsignedBigInteger('designation_id')->nullable();
                $table->json('target_employee_ids')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('attachment_path')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shifts')) {
            Schema::create('shifts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->time('start_time');
                $table->time('end_time');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('public_holidays')) {
            Schema::create('public_holidays', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->date('date');
                $table->text('description')->nullable();
                $table->boolean('is_recurring')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('report_templates')) {
            Schema::create('report_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->nullable();
                $table->text('description')->nullable();
                $table->json('config')->nullable();
                $table->longText('layout')->nullable();
                $table->boolean('is_premium')->default(false);
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('last_run_at')->nullable();
                $table->unsignedInteger('usage_count')->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('blood_donors') && !Schema::hasColumn('blood_donors', 'status')) {
            Schema::table('blood_donors', function (Blueprint $table) {
                $table->string('status')->default('active');
            });
        }
    }

    public function down(): void
    {
        foreach (['report_templates', 'public_holidays', 'shifts', 'hr_announcements', 'training_enrollments', 'training_programs', 'leave_types', 'performance_appraisals', 'e_prescription_templates'] as $t) {
            if (Schema::hasTable($t)) Schema::drop($t);
        }
    }
};
