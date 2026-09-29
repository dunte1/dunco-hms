<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_remittances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('claim_batches')->cascadeOnDelete();
            $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->cascadeOnDelete();
            $table->string('remittance_number');
            $table->date('remittance_date');
            $table->decimal('remitted_amount', 14, 2);
            $table->decimal('variance', 14, 2)->default(0);
            $table->enum('status', ['received', 'reconciled', 'disputed'])->default('received');
            $table->foreignId('reconciled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reconciled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('batch_id');
            $table->index('insurance_provider_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_remittances');
    }
};
