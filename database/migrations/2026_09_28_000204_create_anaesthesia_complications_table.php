<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anaesthesia_complications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anaesthesia_record_id')->constrained('anaesthesia_records')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('complication_type', [
                'difficult_intubation', 'bronchospasm', 'laryngospasm',
                'cardiac_arrest', 'hypotension', 'hypoxia',
                'malignant_hyperthermia', 'awareness', 'nausea_vomiting', 'other',
            ]);
            $table->enum('severity', ['mild', 'moderate', 'severe', 'life_threatening']);
            $table->text('description');
            $table->text('treatment');
            $table->text('outcome')->nullable();
            $table->timestamp('occurred_at');
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anaesthesia_complications');
    }
};
