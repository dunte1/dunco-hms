<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crossmatch_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_request_id')->constrained('blood_requests')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('blood_group_id')->constrained('blood_groups')->cascadeOnDelete();
            $table->date('sample_date');
            $table->foreignId('requested_by')->constrained('doctors')->cascadeOnDelete();
            $table->enum('result', ['compatible', 'incompatible', 'pending'])->default('pending');
            $table->foreignId('tested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crossmatch_requests');
    }
};
