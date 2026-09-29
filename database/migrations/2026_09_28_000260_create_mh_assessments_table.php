<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mh_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->text('presenting_complaint');
            $table->text('mental_status_examination');
            $table->enum('risk_assessment', ['low', 'moderate', 'high', 'critical'])->default('low');
            $table->boolean('suicidal_ideation')->default(false);
            $table->boolean('homicidal_ideation')->default(false);
            $table->boolean('self_harm_risk')->default(false);
            $table->text('substance_use')->nullable();
            $table->decimal('functioning_score', 5, 2)->nullable();
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mh_assessments');
    }
};
