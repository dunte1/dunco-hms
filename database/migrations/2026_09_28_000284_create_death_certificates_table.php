<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('death_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortuary_record_id')->constrained('mortuary_records')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->string('cause_of_death_primary');
            $table->string('cause_of_death_secondary')->nullable();
            $table->text('contributing_conditions')->nullable();
            $table->foreignId('issued_by')->constrained('doctors');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('death_certificates');
    }
};
