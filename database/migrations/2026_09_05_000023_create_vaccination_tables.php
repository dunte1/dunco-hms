<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('manufacturer')->nullable();
            $table->integer('dose_count')->default(1);
            $table->integer('stock_quantity')->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('vaccine_id')->constrained('vaccines')->cascadeOnDelete();
            $table->integer('dose_number')->default(1);
            $table->foreignId('administered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('administered_at');
            $table->string('site')->nullable();
            $table->string('batch_number')->nullable();
            $table->text('reaction_notes')->nullable();
            $table->date('next_dose_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccination_records');
        Schema::dropIfExists('vaccines');
    }
};
