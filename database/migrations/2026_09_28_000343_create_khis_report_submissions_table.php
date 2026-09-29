<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('khis_report_submissions', function (Blueprint $table) {
            $table->id();
            $table->enum('report_type', ['moh_731', 'moh_711', 'moh_710', 'surveillance', 'other']);
            $table->unsignedSmallInteger('reporting_period_month');
            $table->unsignedSmallInteger('reporting_period_year');
            $table->string('facility_code')->nullable();
            $table->unsignedInteger('total_patients')->default(0);
            $table->unsignedInteger('total_visits')->default(0);
            $table->json('report_data');
            $table->enum('status', ['draft', 'submitted', 'accepted', 'rejected'])->default('draft');
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->string('response_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khis_report_submissions');
    }
};
