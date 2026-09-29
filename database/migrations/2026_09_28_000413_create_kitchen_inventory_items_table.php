<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->enum('category', ['staples', 'vegetables', 'proteins', 'dairy', 'spices', 'beverages']);
            $table->decimal('quantity', 10, 2);
            $table->enum('unit', ['kg', 'litre', 'packs', 'boxes']);
            $table->decimal('reorder_level', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('last_restocked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_inventory_items');
    }
};
