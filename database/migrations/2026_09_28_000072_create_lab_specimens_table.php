<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lab_specimens', function (Blueprint $table) {
            $table->id();
            $table->string('specimen_number')->unique();
            $table->foreignId('lab_request_id')->constrained('lab_requests')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('specimen_type', 20); // blood, urine, stool, sputum, swab, tissue, csf, other
            $table->string('status', 20)->default('collected'); // collected, received, processing, completed, rejected
            $table->foreignId('collected_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('collected_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_specimens');
    }
};
