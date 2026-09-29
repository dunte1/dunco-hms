<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('duty_roster_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roster_id')->constrained('duty_rosters')->cascadeOnDelete();
            $table->foreignId('nurse_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['scheduled', 'on_duty', 'absent', 'swap', 'leave'])->default('scheduled');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->foreignId('substitute_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['roster_id', 'nurse_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_roster_entries');
    }
};
