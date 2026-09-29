<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adverse_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('chemo_cycle_id')->nullable()->constrained('chemo_cycles')->nullOnDelete();
            $table->foreignId('treatment_plan_id')->nullable()->constrained('oncology_treatment_plans')->nullOnDelete();
            $table->integer('grade')->unsigned();
            $table->enum('event_type', [
                'nausea', 'vomiting', 'fatigue', 'alopecia', 'neutropenia',
                'anemia', 'thrombocytopenia', 'mucositis', 'neuropathy', 'other',
            ]);
            $table->text('description');
            $table->date('onset_date');
            $table->date('resolved_date')->nullable();
            $table->text('management');
            $table->foreignId('reported_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adverse_events');
    }
};
