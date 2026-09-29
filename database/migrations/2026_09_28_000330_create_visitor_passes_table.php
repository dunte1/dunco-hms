<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitor_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_log_id')->nullable()->constrained('visitor_logs')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('badge_number')->nullable();
            $table->string('visit_purpose');
            $table->string('ward_authorized')->nullable();
            $table->datetime('check_in_time');
            $table->datetime('check_out_time')->nullable();
            $table->string('status', 20)->default('active'); // active, completed, expired
            $table->foreignId('issued_by')->constrained('users');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_passes');
    }
};
