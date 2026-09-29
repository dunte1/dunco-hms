<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nurse_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nurse_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->date('allocated_date');
            $table->enum('shift_type', ['day', 'night']);
            $table->integer('bed_range_start')->nullable();
            $table->integer('bed_range_end')->nullable();
            $table->integer('patient_count')->default(0);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->foreignId('allocated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['ward_id', 'allocated_date', 'shift_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurse_allocations');
    }
};
