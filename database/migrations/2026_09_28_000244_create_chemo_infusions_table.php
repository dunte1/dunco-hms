<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chemo_infusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chemo_cycle_id')->constrained('chemo_cycles')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('drug_name');
            $table->string('dose');
            $table->decimal('volume_ml', 8, 2)->nullable();
            $table->decimal('rate', 8, 2)->nullable();
            $table->enum('site', ['arm', 'hand', 'port', 'other']);
            $table->foreignId('nurse_id')->constrained('users');
            $table->enum('status', ['running', 'completed', 'stopped', 'paused'])->default('running');
            $table->text('complications')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chemo_infusions');
    }
};
