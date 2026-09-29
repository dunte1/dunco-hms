<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pep_prep_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('record_type', ['PEP', 'PrEP']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('indication')->nullable();
            $table->text('regimen')->nullable();
            $table->foreignId('prescribed_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['active', 'completed', 'discontinued'])->default('active');
            $table->text('discontinuation_reason')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pep_prep_records');
    }
};
