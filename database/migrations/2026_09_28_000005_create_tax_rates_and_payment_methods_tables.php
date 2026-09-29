<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->decimal('rate', 5, 2)->comment('Percentage, e.g. 16.00 for 16% VAT');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_inclusive')->default(false)->comment('Tax included in price vs added on top');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 20)->unique();
            $table->string('type')->comment('cash, card, mobile_money, insurance, bank_transfer, credit');
            $table->boolean('is_active')->default(true);
            $table->json('configuration')->nullable()->comment('Provider-specific settings');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('tax_rates');
    }
};
