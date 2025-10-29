<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_equipment_id')->constrained('lab_equipment')->cascadeOnDelete();
            $table->foreignId('lab_request_id')->constrained('lab_requests')->cascadeOnDelete();
            $table->json('raw_data'); // Raw data from equipment
            $table->json('processed_data'); // Processed/parsed data
            $table->string('result_status', 20)->default('pending'); // pending, processed, verified
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_results');
    }
};
