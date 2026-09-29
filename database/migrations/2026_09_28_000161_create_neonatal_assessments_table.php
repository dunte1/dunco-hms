<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('neonatal_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newborn_id')->constrained('newborns')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->integer('weight_grams');
            $table->decimal('length_cm', 5, 1);
            $table->decimal('head_circumference_cm', 4, 1);
            $table->decimal('temperature', 4, 1);
            $table->integer('heart_rate');
            $table->integer('respiratory_rate');
            $table->enum('feeding_type', ['breast', 'formula', 'mixed']);
            $table->boolean('stool_passed');
            $table->enum('jaundice', ['none', 'mild', 'moderate', 'severe']);
            $table->enum('reflexes', ['present', 'absent']);
            $table->boolean('cried_at_birth');
            $table->text('notes')->nullable();
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('neonatal_assessments');
    }
};
