<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('who_safety_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ot_schedule_id')->constrained('ot_schedules')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('checklist_type', ['sign_in', 'time_out', 'sign_out']);
            $table->boolean('completed')->default(false);
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('site_marked')->default(false);
            $table->boolean('consent_confirmed')->default(false);
            $table->boolean('anaesthesia_safety_confirmed')->default(false);
            $table->boolean('instruments_counted')->default(false);
            $table->boolean('equipment_checked')->default(false);
            $table->boolean('key_concerns_communicated')->default(false);
            $table->boolean('prophylactic_antibiotics_given')->default(false);
            $table->boolean('essential_imaging_displayed')->default(false);
            $table->boolean('patient_identity_confirmed')->default(false);
            $table->boolean('surgical_site_confirmed')->default(false);
            $table->boolean('allergies_confirmed')->default(false);
            $table->boolean('blood_loss_risk_assessed')->default(false);
            $table->boolean('team_introduced')->default(false);
            $table->boolean('recovery_plan_discussed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('who_safety_checklists');
    }
};
