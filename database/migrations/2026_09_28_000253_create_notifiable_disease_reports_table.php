<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifiable_disease_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surveillance_case_id')->constrained('surveillance_cases')->cascadeOnDelete();
            $table->string('report_week');
            $table->string('report_year');
            $table->string('facility_code')->nullable();
            $table->string('disease_name');
            $table->integer('cases_count')->default(0);
            $table->integer('deaths_count')->default(0);
            $table->boolean('reported_to_moh')->default(false);
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifiable_disease_reports');
    }
};
