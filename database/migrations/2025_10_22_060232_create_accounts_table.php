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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense'])->default('expense');
            $table->enum('account_category', [
                'current_asset', 'fixed_asset', 'current_liability', 'long_term_liability',
                'equity', 'operating_revenue', 'other_revenue', 'operating_expense', 'other_expense'
            ])->nullable();
            $table->foreignId('parent_account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->enum('balance_type', ['debit', 'credit'])->default('debit');
            $table->boolean('is_system_account')->default(false); // For system-generated accounts
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_manual_entry')->default(true); // Can transactions be posted manually?
            $table->string('currency', 3)->default('KES');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
