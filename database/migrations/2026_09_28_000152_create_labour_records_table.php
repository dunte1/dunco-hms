<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('labour_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->dateTime('admission_time');
            $table->dateTime('labour_start_time');
            $table->enum('membrane_status', ['intact', 'ruptured']);
            $table->dateTime('rupture_time')->nullable();
            $table->integer('cervical_dilation')->nullable();
            $table->enum('liquor', ['clear', 'meconium', 'blood'])->nullable();
            $table->string('presenting_part', 50)->nullable();
            $table->text('labour_progress')->nullable();
            $table->text('complications')->nullable();
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labour_records');
    }
};
