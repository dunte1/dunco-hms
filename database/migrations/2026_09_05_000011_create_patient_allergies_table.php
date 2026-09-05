<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('allergen');
            $table->enum('allergen_type', ['drug', 'food', 'environmental', 'other'])->default('drug');
            $table->string('reaction')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe', 'anaphylaxis'])->default('moderate');
            $table->date('onset_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_allergies');
    }
};
