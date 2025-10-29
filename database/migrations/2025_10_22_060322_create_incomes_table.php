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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('income_number')->unique();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->enum('income_category', [
                'patient_services', 'pharmacy_sales', 'lab_tests', 'radiology', 
                'consultation_fees', 'admission_fees', 'surgery_fees', 
                'ambulance_services', 'other'
            ])->default('other');
            $table->string('source')->nullable(); // Patient name, insurance company, etc.
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->date('income_date');
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'cheque', 'm_pesa', 'insurance'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
