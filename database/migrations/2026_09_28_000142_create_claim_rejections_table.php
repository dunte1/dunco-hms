<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_claim_id')->constrained('insurance_claims')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('claim_batches')->nullOnDelete();
            $table->string('rejection_code');
            $table->text('rejection_reason');
            $table->decimal('amount_rejected', 12, 2)->default(0);
            $table->enum('action_taken', ['resubmit', 'withdraw', 'write_off', 'appeal'])->nullable();
            $table->foreignId('action_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('action_at')->nullable();
            $table->timestamps();

            $table->index('insurance_claim_id');
            $table->index('batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_rejections');
    }
};
