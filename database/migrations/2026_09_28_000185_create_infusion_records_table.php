<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('infusion_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('icu_admission_id')->constrained('icu_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('drug_name', 100);
            $table->string('concentration', 50)->nullable();
            $table->decimal('rate_ml_hr', 8, 2);
            $table->decimal('volume_infused_ml', 8, 1)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('status', 20)->default('running'); // running, stopped, paused
            $table->foreignId('stopped_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason_for_stop')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infusion_records');
    }
};
