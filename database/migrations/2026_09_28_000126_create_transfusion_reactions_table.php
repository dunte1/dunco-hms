<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfusion_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfusion_id')->constrained('transfusions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('reaction_type', ['febrile', 'allergic', 'hemolytic', 'trx', 'TRALI', 'TACO', 'other']);
            $table->enum('severity', ['mild', 'moderate', 'severe', 'life_threatening']);
            $table->text('symptoms');
            $table->timestamp('onset_time');
            $table->text('treatment_given')->nullable();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('reported_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfusion_reactions');
    }
};
