<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_adherence_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tb_treatment_id')->constrained('tb_treatments')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients');
            $table->date('log_date');
            $table->integer('doses_expected');
            $table->integer('doses_taken');
            $table->decimal('adherence_percentage', 5, 2);
            $table->string('missed_reason')->nullable();
            $table->boolean('counselling_done')->default(false);
            $table->foreignId('logged_by')->constrained('users');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_adherence_logs');
    }
};
