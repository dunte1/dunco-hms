<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('specimens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ot_schedule_id')->nullable()->constrained('ot_schedules')->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('specimen_type', ['tissue', 'blood', 'fluid', 'other']);
            $table->string('description');
            $table->string('collection_site');
            $table->string('container_type');
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('collected_at');
            $table->boolean('sent_to_lab')->default(false);
            $table->foreignId('lab_request_id')->nullable()->constrained('lab_requests')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specimens');
    }
};
