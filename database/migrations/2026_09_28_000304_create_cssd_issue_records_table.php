<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cssd_issue_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_set_id')->constrained('instrument_sets');
            $table->foreignId('issued_to_user_id')->constrained('users');
            $table->foreignId('theatre_schedule_id')->nullable()->constrained('ot_schedules')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expected_return_at')->nullable();
            $table->enum('status', ['issued', 'overdue', 'returned'])->default('issued');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cssd_issue_records');
    }
};
