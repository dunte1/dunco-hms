<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->enum('living_situation', ['alone', 'family', 'institutional', 'homeless']);
            $table->text('income_source')->nullable();
            $table->enum('financial_status', ['stable', 'unstable', 'crisis']);
            $table->enum('family_support', ['strong', 'limited', 'none']);
            $table->boolean('transport_needs')->default(false);
            $table->boolean('housing_needs')->default(false);
            $table->boolean('legal_needs')->default(false);
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_assessments');
    }
};
