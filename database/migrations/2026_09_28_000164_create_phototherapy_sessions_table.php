<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phototherapy_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newborn_id')->constrained('newborns')->cascadeOnDelete();
            $table->foreignId('nicu_admission_id')->nullable()->constrained('nicu_admissions')->nullOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->decimal('duration_hours', 5, 1)->nullable();
            $table->enum('light_type', ['LED', 'fiber_optic']);
            $table->decimal('bilirubin_before', 5, 1)->nullable();
            $table->decimal('bilirubin_after', 5, 1)->nullable();
            $table->boolean('eye_protection')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phototherapy_sessions');
    }
};
