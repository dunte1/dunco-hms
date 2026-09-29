<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_screening_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('blood_donations')->cascadeOnDelete();
            $table->enum('hiv_test', ['pending', 'negative', 'positive', 'inconclusive'])->default('pending');
            $table->enum('hepatitis_b_test', ['pending', 'negative', 'positive', 'inconclusive'])->default('pending');
            $table->enum('hepatitis_c_test', ['pending', 'negative', 'positive', 'inconclusive'])->default('pending');
            $table->enum('syphilis_test', ['pending', 'negative', 'positive', 'inconclusive'])->default('pending');
            $table->enum('malaria_test', ['pending', 'negative', 'positive', 'inconclusive'])->default('pending');
            $table->string('blood_group_confirmation')->nullable();
            $table->foreignId('screened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('screened_at')->nullable();
            $table->boolean('is_eligible')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_screening_results');
    }
};
