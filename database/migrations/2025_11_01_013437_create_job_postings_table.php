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
        if (!Schema::hasTable('job_postings')) {
            Schema::create('job_postings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->foreignId('department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
                $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
                $table->text('description');
                $table->text('requirements')->nullable();
                $table->text('responsibilities')->nullable();
                $table->string('employment_type', 50); // full-time, part-time, contract
                $table->decimal('salary_min', 10, 2)->nullable();
                $table->decimal('salary_max', 10, 2)->nullable();
                $table->string('location')->nullable();
                $table->date('application_deadline')->nullable();
                $table->integer('vacancies')->default(1);
                $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        } else {
            // Table exists, add missing columns if needed
            Schema::table('job_postings', function (Blueprint $table) {
                if (!Schema::hasColumn('job_postings', 'department_id')) {
                    $table->foreignId('department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
                }
                if (!Schema::hasColumn('job_postings', 'designation_id')) {
                    $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
                }
                if (!Schema::hasColumn('job_postings', 'vacancies')) {
                    $table->integer('vacancies')->default(1);
                }
                if (!Schema::hasColumn('job_postings', 'published_at')) {
                    $table->timestamp('published_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
