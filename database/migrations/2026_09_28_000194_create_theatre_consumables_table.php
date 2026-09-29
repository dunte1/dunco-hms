<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('theatre_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ot_schedule_id')->constrained('ot_schedules')->cascadeOnDelete();
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->nullOnDelete();
            $table->string('item_name');
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->string('batch_number')->nullable();
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theatre_consumables');
    }
};
