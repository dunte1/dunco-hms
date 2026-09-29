<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_admission_id')->constrained('emergency_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->enum('disposition_type', ['discharged', 'admitted', 'theatre', 'icu', 'referred', 'transferred', 'deceased']);
            $table->string('destination_ward')->nullable();
            $table->text('discharge_notes')->nullable();
            $table->text('discharge_instructions')->nullable();
            $table->foreignId('discharged_by')->nullable()->constrained('doctors')->nullOnDelete();
            $table->datetime('discharged_at');
            $table->boolean('billing_deferred')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_dispositions');
    }
};
