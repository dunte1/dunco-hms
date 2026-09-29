<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->nullable()->constrained('maintenance_requests')->nullOnDelete();
            $table->foreignId('equipment_id')->nullable()->constrained('medical_equipment')->nullOnDelete();
            $table->enum('work_type', ['corrective', 'preventive', 'emergency']);
            $table->string('title');
            $table->text('description');
            $table->text('parts_used')->nullable();
            $table->decimal('labor_hours', 6, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->enum('status', ['open', 'in_progress', 'on_hold', 'completed'])->default('open');
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
