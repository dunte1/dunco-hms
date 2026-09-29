<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_merge_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('primary_patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('duplicate_patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('merged_by')->constrained('users')->cascadeOnDelete();
            $table->string('merge_reason')->nullable();
            $table->boolean('data_migrated')->default(false);
            $table->timestamp('merged_at');
            $table->timestamp('reversed_at')->nullable();
            $table->foreignId('reversed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_merge_log');
    }
};
