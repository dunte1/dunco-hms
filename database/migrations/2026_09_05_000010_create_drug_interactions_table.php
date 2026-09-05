<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drug_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_a_id')->constrained('medicines')->cascadeOnDelete();
            $table->foreignId('drug_b_id')->constrained('medicines')->cascadeOnDelete();
            $table->enum('severity', ['critical', 'severe', 'moderate', 'mild'])->default('moderate');
            $table->text('description');
            $table->text('clinical_effect')->nullable();
            $table->text('management_advice')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drug_interactions');
    }
};
