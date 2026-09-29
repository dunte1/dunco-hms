<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cssd_cycle_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('cssd_batches')->nullOnDelete();
            $table->foreignId('instrument_set_id')->nullable()->constrained('instrument_sets')->nullOnDelete();
            $table->enum('cycle_type', ['decontamination', 'cleaning', 'packing', 'sterilization']);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->enum('status', ['in_progress', 'completed', 'failed'])->default('in_progress');
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cssd_cycle_records');
    }
};
