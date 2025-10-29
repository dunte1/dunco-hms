<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('death_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('deceased_name');
            $table->string('deceased_phone')->nullable();
            $table->date('death_date');
            $table->time('death_time');
            $table->integer('age_at_death');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('cause_of_death');
            $table->string('place_of_death', 100); // hospital, home, other
            $table->foreignId('attending_doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('attending_nurse_id')->nullable()->constrained('nurses')->nullOnDelete();
            $table->text('circumstances')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active'); // active, archived
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('death_reports');
    }
};
