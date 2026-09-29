<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('outbreak_events', function (Blueprint $table) {
            $table->id();
            $table->string('disease_name');
            $table->date('start_date');
            $table->json('facility_ids')->nullable();
            $table->integer('total_cases')->default(0);
            $table->integer('total_deaths')->default(0);
            $table->string('status')->default('suspected');
            $table->foreignId('declared_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('declared_at');
            $table->timestamp('contained_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->text('investigation_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbreak_events');
    }
};
