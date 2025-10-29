<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name');
            $table->string('visitor_phone');
            $table->string('visitor_email')->nullable();
            $table->string('visitor_id_number')->nullable(); // ID card number
            $table->string('visitor_type', 50); // patient_visitor, contractor, vendor, guest
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('patient_name')->nullable();
            $table->string('purpose', 100);
            $table->string('department', 100)->nullable();
            $table->string('contact_person')->nullable();
            $table->datetime('check_in_time');
            $table->datetime('check_out_time')->nullable();
            $table->string('status', 20)->default('checked_in'); // checked_in, checked_out, expired
            $table->text('notes')->nullable();
            $table->string('badge_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
