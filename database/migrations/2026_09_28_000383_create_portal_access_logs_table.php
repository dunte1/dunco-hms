<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portal_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_portal_account_id')->constrained('patient_portal_accounts')->cascadeOnDelete();
            $table->string('action', 50); // login, view_results, view_prescriptions, view_billing, view_profile
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_access_logs');
    }
};
