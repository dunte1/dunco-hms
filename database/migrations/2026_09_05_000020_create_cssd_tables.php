<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cssd_instruments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('status', ['available', 'in_use', 'sterilizing', 'defective'])->default('available');
            $table->string('location')->nullable();
            $table->timestamp('last_sterilized_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cssd_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->json('instrument_ids')->nullable();
            $table->string('sterilization_method')->default('autoclave');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('temperature', 5, 1)->nullable();
            $table->decimal('pressure', 5, 1)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('status', ['processing', 'sterilized', 'failed'])->default('processing');
            $table->timestamp('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cssd_batches');
        Schema::dropIfExists('cssd_instruments');
    }
};
