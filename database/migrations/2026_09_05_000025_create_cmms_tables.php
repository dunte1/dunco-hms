<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medical_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['diagnostic', 'therapeutic', 'surgical', 'life_support', 'laboratory', 'other'])->default('other');
            $table->string('department')->nullable();
            $table->string('model_number')->nullable();
            $table->string('serial_number')->unique();
            $table->string('manufacturer')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->enum('status', ['operational', 'maintenance', 'out_of_service', 'retired'])->default('operational');
            $table->string('location')->nullable();
            $table->timestamp('last_maintenance')->nullable();
            $table->timestamp('next_maintenance')->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('medical_equipment')->cascadeOnDelete();
            $table->enum('maintenance_type', ['preventive', 'corrective', 'calibration', 'emergency'])->default('preventive');
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('performed_at');
            $table->text('description')->nullable();
            $table->text('parts_replaced')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->enum('status', ['completed', 'in_progress', 'pending'])->default('completed');
            $table->text('next_action')->nullable();
            $table->date('next_due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('medical_equipment');
    }
};
