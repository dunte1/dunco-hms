<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_assessment_records', function (Blueprint $table) {
            $table->id();
            $table->string('area_or_activity');
            $table->text('hazard_identified');
            $table->enum('risk_level', ['low', 'moderate', 'high', 'critical']);
            $table->text('existing_controls');
            $table->text('additional_controls')->nullable();
            $table->enum('likelihood', ['unlikely', 'possible', 'likely', 'almost_certain']);
            $table->enum('consequence', ['insignificant', 'minor', 'moderate', 'major', 'catastrophic']);
            $table->enum('residual_risk_level', ['low', 'moderate', 'high', 'critical']);
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->date('next_review_date')->nullable();
            $table->enum('status', ['active', 'overdue', 'reviewed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessment_records');
    }
};
