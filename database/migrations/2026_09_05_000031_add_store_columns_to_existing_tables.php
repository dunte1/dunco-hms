<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_movements', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('medicine_id')->constrained('stores')->nullOnDelete();
            }
            if (!Schema::hasColumn('stock_movements', 'to_store_id')) {
                $table->foreignId('to_store_id')->nullable()->after('store_id')->constrained('stores')->nullOnDelete();
            }
            if (!Schema::hasColumn('stock_movements', 'batch_id')) {
                $table->foreignId('batch_id')->nullable()->after('to_store_id')->constrained('medicine_batches')->nullOnDelete();
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('supplier_id')->constrained('stores')->nullOnDelete();
            }
        });

        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'total_stock')) {
                $table->integer('total_stock')->default(0)->after('stock_quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
            $table->dropForeign(['to_store_id']);
            $table->dropColumn('to_store_id');
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
        Schema::table('purchase_orders', function ($table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
        Schema::table('medicines', function ($table) {
            $table->dropColumn('total_stock');
        });
    }
};
