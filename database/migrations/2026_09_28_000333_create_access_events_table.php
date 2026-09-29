<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('event_type', 20); // entry, exit, attempt_denied
            $table->string('location');
            $table->string('access_method', 20); // badge, biometric, key, manual
            $table->string('device_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->datetime('event_time');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_events');
    }
};
