<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('notes');
        });

        Schema::table('store_stock', function (Blueprint $table) {
            $table->integer('reorder_quantity')->default(0)->after('minimum_stock');
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->integer('reorder_quantity')->default(0)->after('minimum_stock');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function ($table) {
            $table->dropColumn('reorder_quantity');
        });
        Schema::table('store_stock', function ($table) {
            $table->dropColumn('reorder_quantity');
        });
        Schema::table('purchase_orders', function ($table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
