<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bi_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name', 100);
            $table->string('metric_type', 50); // revenue, patient_count, occupancy, etc.
            $table->json('data_points'); // Time series data
            $table->date('date_from');
            $table->date('date_to');
            $table->string('granularity', 20); // daily, weekly, monthly, yearly
            $table->json('predictions')->nullable(); // AI predictions
            $table->text('insights')->nullable(); // AI-generated insights
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bi_analytics');
    }
};
