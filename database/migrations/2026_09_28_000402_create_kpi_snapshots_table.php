<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_definition_id')->constrained('kpi_definitions')->cascadeOnDelete();
            $table->date('snapshot_date');
            $table->decimal('actual_value', 14, 4);
            $table->decimal('target_value', 12, 2)->nullable();
            $table->string('status')->default('insufficient_data'); // met, not_met, insufficient_data
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['kpi_definition_id', 'snapshot_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_snapshots');
    }
};
