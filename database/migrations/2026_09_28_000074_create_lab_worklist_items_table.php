<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lab_worklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worklist_id')->constrained('lab_worklists')->cascadeOnDelete();
            $table->foreignId('lab_request_id')->constrained('lab_requests')->cascadeOnDelete();
            $table->foreignId('lab_request_item_id')->constrained('lab_request_items')->cascadeOnDelete();
            $table->string('priority', 10)->default('routine'); // routine, urgent
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('pending'); // pending, in_progress, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_worklist_items');
    }
};
