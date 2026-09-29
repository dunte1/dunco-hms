<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->cascadeOnDelete();
            $table->integer('claim_count')->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'accepted', 'rejected', 'partial'])->default('draft');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('response_received_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('insurance_provider_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_batches');
    }
};
