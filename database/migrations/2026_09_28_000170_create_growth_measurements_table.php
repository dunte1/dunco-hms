<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('growth_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('recorded_date');
            $table->integer('weight_grams');
            $table->decimal('height_cm', 5, 1);
            $table->decimal('head_circumference_cm', 4, 1);
            $table->decimal('bmi', 5, 2)->nullable();
            $table->decimal('weight_for_age_zscore', 5, 2)->nullable();
            $table->decimal('height_for_age_zscore', 5, 2)->nullable();
            $table->decimal('weight_for_height_zscore', 5, 2)->nullable();
            $table->enum('malnutrition_status', ['none', 'mild', 'moderate', 'severe'])->default('none');
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_measurements');
    }
};
