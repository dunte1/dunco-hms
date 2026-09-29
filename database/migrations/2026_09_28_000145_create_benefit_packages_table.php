<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefit_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_provider_id')->nullable()->constrained('insurance_providers')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('coverage_percentage', 5, 2);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('insurance_provider_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefit_packages');
    }
};
