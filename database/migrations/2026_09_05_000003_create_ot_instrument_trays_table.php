<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ot_instrument_trays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['sterile', 'used', 'contaminated', 'maintenance'])->default('sterile');
            $table->timestamp('sterilized_at')->nullable();
            $table->timestamp('sterilization_expiry')->nullable();
            $table->foreignId('last_used_schedule_id')->nullable()->constrained('ot_schedules')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ot_instrument_trays');
    }
};
