<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partner_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('partner_name')->nullable();
            $table->string('partner_phone')->nullable();
            $table->boolean('partner_traced')->default(false);
            $table->enum('notification_method', ['self', 'facility', 'unknown'])->default('unknown');
            $table->enum('status', ['pending', 'notified', 'tested', 'linked'])->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->foreignId('hts_encounter_id')->nullable()->constrained('hts_encounters')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_notifications');
    }
};
