<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incubator_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nicu_admission_id')->constrained('nicu_admissions')->cascadeOnDelete();
            $table->string('incubator_id');
            $table->dateTime('assigned_at');
            $table->dateTime('removed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incubator_assignments');
    }
};
