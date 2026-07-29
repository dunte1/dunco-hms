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
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_insurance_id')->constrained('patient_insurance')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->date('claim_date');
            $table->date('service_date');
            $table->decimal('claimed_amount', 10, 2);
            $table->decimal('approved_amount', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'submitted', 'under_review', 'approved', 'partially_approved', 'rejected', 'paid'])->default('pending');
            $table->string('diagnosis_code')->nullable();
            $table->text('diagnosis_description')->nullable();
            $table->text('treatment_details')->nullable();
            $table->text('documents')->nullable(); // JSON array of document paths
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('insurance_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
    }
};
