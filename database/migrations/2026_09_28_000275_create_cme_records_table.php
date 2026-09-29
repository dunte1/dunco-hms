<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cme_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('title');
            $table->string('provider');
            $table->decimal('credits_earned', 5, 1);
            $table->enum('category', ['clinical', 'ethics', 'quality', 'safety', 'admin']);
            $table->date('date_from');
            $table->date('date_to');
            $table->string('certificate_path')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cme_records');
    }
};
