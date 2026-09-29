<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sterilizer_runs', function (Blueprint $table) {
            $table->id();
            $table->string('sterilizer_name');
            $table->integer('load_number');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->decimal('temperature', 5, 1)->nullable();
            $table->decimal('pressure', 6, 1)->nullable();
            $table->integer('exposure_time_minutes')->nullable();
            $table->enum('cycle_type', ['gravity', 'pre_vac', 'post_vac']);
            $table->enum('status', ['completed', 'failed'])->default('completed');
            $table->foreignId('operator_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sterilizer_runs');
    }
};
