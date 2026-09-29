<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('security_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->string('incident_type', 50); // theft, assault, trespass, vandalism, unauthorized_access, drug_related, other
            $table->string('severity', 20); // low, medium, high, critical
            $table->text('description');
            $table->string('location');
            $table->foreignId('reported_by')->constrained('users');
            $table->datetime('reported_at');
            $table->text('witnesses')->nullable();
            $table->text('actions_taken')->nullable();
            $table->string('status', 20)->default('reported'); // reported, investigating, resolved, closed
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_incidents');
    }
};
