<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('patient_email')->nullable();
            $table->string('patient_phone')->nullable();
            $table->text('testimonial');
            $table->integer('rating')->default(5); // 1-5 stars
            $table->string('treatment_received')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('patient_photo')->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->boolean('is_featured')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
