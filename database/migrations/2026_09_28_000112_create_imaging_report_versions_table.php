<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imaging_report_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_request_id')->constrained('radiology_requests')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->text('findings')->nullable();
            $table->text('impression')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('status', 20)->default('draft'); // draft, final, amended
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imaging_report_versions');
    }
};
