<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ipc_audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_type');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->date('audit_date');
            $table->decimal('score', 5, 2);
            $table->text('findings');
            $table->text('corrective_actions')->nullable();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->date('next_audit_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipc_audits');
    }
};
