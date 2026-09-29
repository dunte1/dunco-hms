<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('blood_donors')->cascadeOnDelete();
            $table->foreignId('blood_group_id')->constrained('blood_groups')->cascadeOnDelete();
            $table->integer('volume_ml');
            $table->date('donation_date');
            $table->enum('donation_type', ['whole_blood', 'plasma', 'platelets', 'apheresis']);
            $table->decimal('hemoglobin_g_dl', 4, 1)->nullable();
            $table->integer('blood_pressure_sys')->nullable();
            $table->integer('blood_pressure_dia')->nullable();
            $table->integer('pulse_rate')->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->enum('status', ['eligible', 'ineligible', 'completed'])->default('eligible');
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_donations');
    }
};
