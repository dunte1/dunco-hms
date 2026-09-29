<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surveillance_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('disease_name');
            $table->string('icd_code')->nullable();
            $table->string('notification_type');
            $table->date('case_date');
            $table->foreignId('facility_id')->nullable()->constrained('hospital_branches')->nullOnDelete();
            $table->string('county')->nullable();
            $table->string('sub_county')->nullable();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->date('confirmed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveillance_cases');
    }
};
