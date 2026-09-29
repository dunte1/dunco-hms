<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mortality_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('death_report_id')->nullable()->constrained('death_reports')->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients');
            $table->date('review_date');
            $table->string('review_type', 30); // peer_review, morbidity_mortality, clinical_audit
            $table->text('diagnosis');
            $table->text('contributing_factors');
            $table->string('preventability', 30); // preventable, potentially_preventable, not_preventable, under_review
            $table->text('recommendations');
            $table->string('status', 20)->default('pending'); // pending, reviewed, completed
            $table->foreignId('reviewed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortality_reviews');
    }
};
