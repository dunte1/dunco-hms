<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tele_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tele_session_id')->constrained('telemedicine_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role', 20); // host, participant, observer
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->string('status', 20)->default('invited'); // invited, joined, left
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tele_participants');
    }
};
