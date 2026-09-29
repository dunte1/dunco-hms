<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->date('collected_date');
            $table->date('processed_date')->nullable();
            $table->integer('total_items')->default(0);
            $table->enum('status', ['collected', 'washing', 'drying', 'folding', 'complete'])->default('collected');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('ward_ids')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_batches');
    }
};
