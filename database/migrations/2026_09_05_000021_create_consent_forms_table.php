<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->enum('consent_type', ['procedure', 'anesthesia', 'blood_transfusion', 'data_sharing', 'research'])->default('procedure');
            $table->string('procedure_name')->nullable();
            $table->text('description')->nullable();
            $table->text('risks_disclosed')->nullable();
            $table->text('alternatives_disclosed')->nullable();
            $table->string('patient_signature_path')->nullable();
            $table->string('witness_signature_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['draft', 'pending', 'signed', 'revoked', 'expired'])->default('draft');
            $table->string('ip_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_forms');
    }
};
