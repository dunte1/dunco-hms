<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('insurance_api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_insurance_id')->constrained('patient_insurance')->cascadeOnDelete();
            $table->string('api_provider', 100); // Insurance company API
            $table->string('request_type', 50); // verification, claim, eligibility
            $table->json('request_data');
            $table->json('response_data');
            $table->integer('response_code');
            $table->string('status', 20); // success, failed, pending
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_api_logs');
    }
};
