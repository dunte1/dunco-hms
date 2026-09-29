<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // M22 Dental
        Schema::create('dental_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained('users')->cascadeOnDelete();
            $table->string('tooth_number')->nullable();
            $table->enum('procedure_type', ['extraction', 'filling', 'scaling', 'root_canal', 'crown', 'prosthetic', 'other']);
            $table->text('diagnosis')->nullable();
            $table->text('treatment_notes');
            $table->decimal('cost', 10, 2)->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed'])->default('planned');
            $table->timestamps();
        });

        // M23 Ophthalmology
        Schema::create('eye_examination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('examiner_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('visual_acuity_right', 3, 1)->nullable();
            $table->decimal('visual_acuity_left', 3, 1)->nullable();
            $table->string('iop_right')->nullable();
            $table->string('iop_left')->nullable();
            $table->text('refraction_right')->nullable();
            $table->text('refraction_left')->nullable();
            $table->text('diagnosis');
            $table->text('treatment')->nullable();
            $table->boolean('glasses_prescribed')->default(false);
            $table->timestamps();
        });

        // M24 ENT
        Schema::create('ent_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('examiner_id')->constrained('users')->cascadeOnDelete();
            $table->text('ear_findings')->nullable();
            $table->text('nose_findings')->nullable();
            $table->text('throat_findings')->nullable();
            $table->text('hearing_test')->nullable();
            $table->text('endoscopy_findings')->nullable();
            $table->text('diagnosis');
            $table->text('treatment')->nullable();
            $table->timestamps();
        });

        // M25 Physio/OT
        Schema::create('rehab_session_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('therapist_id')->constrained('users')->cascadeOnDelete();
            $table->enum('session_type', ['physiotherapy', 'occupational_therapy']);
            $table->text('treatment_area');
            $table->text('session_notes');
            $table->text('exercises_performed')->nullable();
            $table->text('progress_notes')->nullable();
            $table->date('next_session_date')->nullable();
            $table->timestamps();
        });

        // M26 Nutrition
        Schema::create('nutrition_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('nutritionist_id')->constrained('users')->cascadeOnDelete();
            $table->enum('assessment_type', ['screening', 'assessment', 'follow_up']);
            $table->decimal('bmi', 4, 1)->nullable();
            $table->enum('malnutrition_risk', ['none', 'low', 'moderate', 'high']);
            $table->text('diet_plan');
            $table->integer('calorie_target')->nullable();
            $table->decimal('protein_target', 5, 1)->nullable();
            $table->text('notes');
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_records');
        Schema::dropIfExists('rehab_session_records');
        Schema::dropIfExists('ent_records');
        Schema::dropIfExists('eye_examination_records');
        Schema::dropIfExists('dental_records');
    }
};
