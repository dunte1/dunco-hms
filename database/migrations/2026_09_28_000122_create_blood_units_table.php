<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_inventory_id')->constrained('blood_inventory')->cascadeOnDelete();
            $table->foreignId('donation_id')->nullable()->constrained('blood_donations')->nullOnDelete();
            $table->string('unit_number')->unique();
            $table->foreignId('blood_group_id')->constrained('blood_groups')->cascadeOnDelete();
            $table->integer('volume_ml');
            $table->date('expiry_date');
            $table->string('status', 20)->default('available'); // available, reserved, issued, expired, returned, discarded
            $table->foreignId('reserved_for_patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_units');
    }
};
