<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('incident_date');
            $table->text('description');
            $table->enum('category', ['warning', 'verbal_written', 'suspension', 'termination', 'other']);
            $table->enum('severity', ['minor', 'moderate', 'major', 'critical']);
            $table->text('action_taken');
            $table->foreignId('action_by')->constrained('users');
            $table->enum('status', ['open', 'resolved', 'closed'])->default('open');
            $table->date('resolution_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_records');
    }
};
