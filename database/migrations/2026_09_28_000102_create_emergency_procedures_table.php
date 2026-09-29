<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_admission_id')->constrained('emergency_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('procedure_name');
            $table->text('description')->nullable();
            $table->string('body_site')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('doctors')->nullOnDelete();
            $table->datetime('performed_at');
            $table->string('outcome')->nullable();
            $table->text('complications')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_procedures');
    }
};
