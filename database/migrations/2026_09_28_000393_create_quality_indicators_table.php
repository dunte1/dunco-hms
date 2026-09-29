<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quality_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description');
            $table->text('formula')->nullable();
            $table->decimal('target_value', 10, 2)->nullable();
            $table->string('unit')->nullable();
            $table->string('category', 30); // clinical, operational, financial, patient_experience
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_indicators');
    }
};
