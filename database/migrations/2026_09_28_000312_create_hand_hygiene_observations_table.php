<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hand_hygiene_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->date('observation_date');
            $table->integer('opportunities_observed')->default(0);
            $table->integer('hand_washes')->default(0);
            $table->decimal('compliance_rate', 5, 2)->nullable();
            $table->string('technique_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hand_hygiene_observations');
    }
};
