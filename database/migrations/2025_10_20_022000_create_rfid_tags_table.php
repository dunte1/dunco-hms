<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rfid_tags', function (Blueprint $table) {
            $table->id();
            $table->string('tag_id')->unique();
            $table->string('tag_type', 50); // patient, staff, equipment, visitor
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('associated_name')->nullable(); // For non-patient/staff tags
            $table->string('status', 20)->default('active'); // active, inactive, lost, damaged
            $table->datetime('last_seen')->nullable();
            $table->string('last_location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_tags');
    }
};
