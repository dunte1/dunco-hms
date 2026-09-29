<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('indicator_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('quality_indicators');
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->decimal('numerator', 12, 2)->nullable();
            $table->decimal('denominator', 12, 2)->nullable();
            $table->decimal('actual_value', 10, 4)->nullable();
            $table->string('status', 20)->default('insufficient_data'); // met, not_met, insufficient_data
            $table->foreignId('recorded_by')->constrained('users');
            $table->datetime('recorded_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_values');
    }
};
