<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calibration_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('medical_equipment')->cascadeOnDelete();
            $table->date('calibration_date');
            $table->date('next_due_date');
            $table->enum('result', ['pass', 'fail', 'conditional']);
            $table->string('certificate_number')->nullable();
            $table->boolean('performed_by_vendor')->default(false);
            $table->string('vendor_name')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calibration_records');
    }
};
