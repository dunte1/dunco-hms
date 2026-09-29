<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contrast_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_request_id')->constrained('radiology_requests')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('contrast_type', 20); // iodine, gadolinium, barium, none
            $table->string('contrast_agent')->nullable();
            $table->decimal('volume_ml', 8, 2)->nullable();
            $table->string('route', 20); // iv, oral, rectal
            $table->text('reaction_notes')->nullable();
            $table->foreignId('administered_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('administered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrast_records');
    }
};
