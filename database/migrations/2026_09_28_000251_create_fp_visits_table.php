<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fp_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('visit_date');
            $table->string('method');
            $table->string('previous_method')->nullable();
            $table->text('side_effects')->nullable();
            $table->integer('satisfaction_score')->nullable();
            $table->foreignId('counseled_by')->constrained('users')->cascadeOnDelete();
            $table->date('next_visit_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fp_visits');
    }
};
