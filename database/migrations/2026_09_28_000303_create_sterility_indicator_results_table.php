<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sterility_indicator_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sterilizer_run_id')->constrained('sterilizer_runs')->cascadeOnDelete();
            $table->enum('indicator_type', ['biological', 'chemical', 'physical']);
            $table->enum('result', ['pass', 'fail']);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sterility_indicator_results');
    }
};
