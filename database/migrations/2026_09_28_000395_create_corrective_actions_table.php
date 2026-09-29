<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 30); // complaint, incident, audit, indicator
            $table->unsignedBigInteger('source_id');
            $table->text('action_description');
            $table->foreignId('responsible_person')->constrained('users');
            $table->date('due_date');
            $table->date('completion_date')->nullable();
            $table->string('status', 20)->default('pending'); // pending, in_progress, completed, overdue
            $table->string('evidence_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrective_actions');
    }
};
