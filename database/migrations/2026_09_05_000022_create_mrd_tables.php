<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mrd_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('file_number')->unique();
            $table->enum('file_type', ['discharge_summary', 'lab_report', 'imaging', 'consent', 'operation_note', 'correspondence', 'other'])->default('other');
            $table->string('physical_location')->nullable();
            $table->enum('status', ['in_library', 'issued', 'returned', 'archived'])->default('in_library');
            $table->string('digitized_path')->nullable();
            $table->timestamp('digitized_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('access_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('mrd_file_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mrd_file_id')->constrained('mrd_files')->cascadeOnDelete();
            $table->enum('action', ['issued', 'returned', 'archived', 'digitized']);
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('issued_to')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mrd_file_movements');
        Schema::dropIfExists('mrd_files');
    }
};
