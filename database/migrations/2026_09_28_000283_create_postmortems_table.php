<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('postmortems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortuary_record_id')->constrained('mortuary_records')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->text('cause_of_death');
            $table->enum('manner_of_death', ['natural', 'accident', 'suicide', 'homicide', 'undetermined']);
            $table->foreignId('performed_by')->nullable()->constrained('doctors')->nullOnDelete();
            $table->date('requested_date');
            $table->date('completed_date')->nullable();
            $table->text('findings')->nullable();
            $table->enum('status', ['requested', 'in_progress', 'completed'])->default('requested');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postmortems');
    }
};
