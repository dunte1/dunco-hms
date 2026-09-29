<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category')->nullable()->comment('e.g. consultation, lab, radiology, pharmacy, procedure');
            $table->text('description')->nullable();
            $table->decimal('default_price', 12, 2)->default(0);
            $table->string('currency', 3)->default('KES');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_taxable')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
