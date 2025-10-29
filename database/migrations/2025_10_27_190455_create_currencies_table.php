<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // ISO 4217 currency code (USD, EUR, KES, etc.)
            $table->string('name'); // Full currency name
            $table->string('symbol', 10); // Currency symbol ($, €, KSh, etc.)
            $table->string('country')->nullable(); // Country or region
            $table->decimal('exchange_rate', 15, 6)->default(1.000000); // Exchange rate to base currency
            $table->boolean('is_base_currency')->default(false); // Is this the base currency?
            $table->boolean('is_active')->default(true); // Is currency active?
            $table->integer('decimal_places')->default(2); // Number of decimal places
            $table->string('position', 10)->default('before'); // Symbol position: before or after
            $table->text('description')->nullable();
            $table->timestamp('last_updated')->nullable(); // When exchange rate was last updated
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};