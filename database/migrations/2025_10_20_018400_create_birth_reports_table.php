<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('birth_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->string('baby_name');
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('mother_phone')->nullable();
            $table->string('father_phone')->nullable();
            $table->date('birth_date');
            $table->time('birth_time');
            $table->enum('gender', ['male', 'female']);
            $table->decimal('birth_weight', 5, 2); // in kg
            $table->decimal('birth_length', 5, 2); // in cm
            $table->string('delivery_type', 50); // normal, cesarean, assisted
            $table->foreignId('attending_doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('attending_nurse_id')->nullable()->constrained('nurses')->nullOnDelete();
            $table->text('complications')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active'); // active, archived
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('birth_reports');
    }
};
