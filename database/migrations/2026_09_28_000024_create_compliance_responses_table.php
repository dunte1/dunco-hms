<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('compliance_items')->cascadeOnDelete();
            $table->foreignId('completed_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['compliant', 'non_compliant', 'not_applicable', 'partial']);
            $table->text('notes')->nullable();
            $table->string('evidence_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('next_due_at')->nullable();
            $table->timestamps();

            $table->index('item_id');
            $table->index('completed_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_responses');
    }
};
