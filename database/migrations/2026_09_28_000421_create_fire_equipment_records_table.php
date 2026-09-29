<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fire_equipment_records', function (Blueprint $table) {
            $table->id();
            $table->enum('equipment_type', ['extinguisher', 'alarm', 'hose', 'blanket', 'station']);
            $table->string('location');
            $table->string('serial_number')->nullable();
            $table->date('last_inspection_date')->nullable();
            $table->date('next_inspection_date')->nullable();
            $table->enum('status', ['good', 'needs_maintenance', 'defective', 'expired'])->default('good');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fire_equipment_records');
    }
};
