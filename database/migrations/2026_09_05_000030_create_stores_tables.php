<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['main', 'pharmacy', 'satellite', 'warehouse', 'emergency', 'ward'])->default('pharmacy');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        Schema::create('store_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock')->default(10);
            $table->integer('maximum_stock')->default(1000);
            $table->decimal('average_cost', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['store_id', 'medicine_id']);
        });

        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('batch_number');
            $table->integer('quantity')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'depleted', 'recalled'])->default('active');
            $table->timestamps();

            $table->index(['medicine_id', 'store_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
        Schema::dropIfExists('store_stock');
        Schema::dropIfExists('stores');
    }
};
