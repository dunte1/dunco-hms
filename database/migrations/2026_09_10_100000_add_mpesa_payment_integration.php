<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('fee_type', 50)->default('invoice_payment');
            $table->string('item_name')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('phone', 20)->nullable();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('mpesa_receipt')->nullable()->index();
            $table->string('status', 20)->default('pending')->index();
            $table->string('result_code')->nullable();
            $table->text('result_desc')->nullable();
            $table->string('source_type', 50)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('transaction_data')->nullable();
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
        });

        Schema::table('sha_service_codes', function (Blueprint $table) {
            $table->string('fee_type', 50)->nullable()->after('category');
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('opd_visit_id')
                ->constrained('payments')->nullOnDelete();
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('opd_visit_id')
                ->constrained('payments')->nullOnDelete();
        });

        Schema::table('ipd_admissions', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('patient_id')
                ->constrained('payments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ipd_admissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
        });

        Schema::table('sha_service_codes', function (Blueprint $table) {
            $table->dropColumn('fee_type');
        });

        Schema::dropIfExists('mpesa_transactions');
    }
};