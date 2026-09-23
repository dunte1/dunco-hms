<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number', 30)->unique();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->foreignId('stocktake_id')->nullable()->constrained('stocktakes')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('quantity_adjustment');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('adjustment_type', 30)->default('correction'); // correction, write_off, damage, expiry, receipt
            $table->string('status', 30)->default('pending'); // pending, approved, rejected
            $table->text('reason')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
