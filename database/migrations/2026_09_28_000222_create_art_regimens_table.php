<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('art_regimens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('care_enrollment_id')->constrained('hiv_care_enrollments')->cascadeOnDelete();
            $table->string('regimen_code');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('reason_for_change', ['switch_to_second_line', 'toxicity', 'pregnancy', 'other'])->nullable();
            $table->enum('regimen_line', ['first', 'second', 'third']);
            $table->foreignId('prescribed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('art_regimens');
    }
};
