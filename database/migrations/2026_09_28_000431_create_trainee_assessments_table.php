<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainee_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('trainee_records')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->enum('assessment_type', ['midterm', 'final', 'clinical', 'skills']);
            $table->decimal('score', 5, 2)->nullable();
            $table->string('grade', 10)->nullable();
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('assessed_by')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_assessments');
    }
};
