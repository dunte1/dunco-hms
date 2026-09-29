<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mortuary_slot_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortuary_record_id')->constrained('mortuary_records')->cascadeOnDelete();
            $table->integer('slot_number');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('removed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortuary_slot_assignments');
    }
};
