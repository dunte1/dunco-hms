<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tb_case_id')->constrained('tb_cases')->cascadeOnDelete();
            $table->string('regimen', 20);
            $table->string('phase', 20);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->json('drugs_given')->nullable();
            $table->string('status', 20)->default('active');
            $table->foreignId('prescribed_by')->constrained('doctors');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_treatments');
    }
};
