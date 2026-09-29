<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('number_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('e.g. patient_mrn, invoice, lab_order, visit');
            $table->string('prefix', 10)->default('');
            $table->integer('next_number')->default(1);
            $table->integer('padding')->default(6)->comment('Zero-pad to this width');
            $table->string('format')->nullable()->comment('sprintf format string, e.g. %s%06d');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_sequences');
    }
};
