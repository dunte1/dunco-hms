<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surgical_waiting_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('procedure_name');
            $table->enum('urgency', ['elective', 'urgent', 'emergency'])->default('elective');
            $table->integer('priority')->default(0);
            $table->foreignId('added_by')->nullable()->constrained('doctors')->nullOnDelete();
            $table->date('added_date');
            $table->date('target_date')->nullable();
            $table->enum('status', ['waiting', 'scheduled', 'cancelled', 'completed'])->default('waiting');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surgical_waiting_list');
    }
};
