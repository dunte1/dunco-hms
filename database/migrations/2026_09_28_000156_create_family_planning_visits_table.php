<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_planning_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('method', ['pills', 'injectable', 'implant', 'IUD', 'condom', 'sterilization', 'withdrawal', 'none']);
            $table->string('previous_method', 50)->nullable();
            $table->text('side_effects')->nullable();
            $table->foreignId('counselled_by')->constrained('users')->cascadeOnDelete();
            $table->date('visit_date');
            $table->date('next_visit_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_planning_visits');
    }
};
