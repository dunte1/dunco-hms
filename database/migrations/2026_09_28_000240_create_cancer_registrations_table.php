<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cancer_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->string('cancer_site');
            $table->string('histology_type');
            $table->string('laterality')->nullable();
            $table->string('grade')->nullable();
            $table->date('diagnosis_date');
            $table->string('tnm_staging_t')->nullable();
            $table->string('tnm_staging_n')->nullable();
            $table->string('tnm_staging_m')->nullable();
            $table->enum('overall_stage', ['I', 'II', 'III', 'IV']);
            $table->enum('status', ['active', 'in_remission', 'completed', 'deceased'])->default('active');
            $table->foreignId('registered_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancer_registrations');
    }
};
