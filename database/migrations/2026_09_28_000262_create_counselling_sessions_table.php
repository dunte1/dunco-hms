<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counselling_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('session_date');
            $table->enum('session_type', ['individual', 'group', 'family', 'crisis']);
            $table->text('presenting_issue');
            $table->text('interventions_used');
            $table->text('patient_response')->nullable();
            $table->enum('risk_level', ['low', 'moderate', 'high'])->default('low');
            $table->date('next_session_date')->nullable();
            $table->foreignId('counsellor_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselling_sessions');
    }
};
