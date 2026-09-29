<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_quality_issues', function (Blueprint $table) {
            $table->id();
            $table->enum('issue_type', ['missing_field', 'inconsistent', 'duplicate', 'incomplete']);
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('table_name')->nullable();
            $table->string('field_name')->nullable();
            $table->text('issue_description');
            $table->enum('severity', ['low', 'medium', 'high']);
            $table->enum('status', ['open', 'acknowledged', 'resolved'])->default('open');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_quality_issues');
    }
};
