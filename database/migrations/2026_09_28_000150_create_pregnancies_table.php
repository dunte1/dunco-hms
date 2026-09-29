<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->integer('gravida');
            $table->integer('parity');
            $table->date('last_menstrual_date');
            $table->date('estimated_due_date');
            $table->integer('current_gestational_weeks')->default(0);
            $table->string('blood_group', 10);
            $table->string('rh_factor', 10);
            $table->enum('hiv_status', ['known_negative', 'known_positive', 'unknown'])->default('unknown');
            $table->text('previous_complications')->nullable();
            $table->boolean('is_high_risk')->default(false);
            $table->text('high_risk_reason')->nullable();
            $table->enum('status', ['active', 'completed', 'abandoned'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregnancies');
    }
};
