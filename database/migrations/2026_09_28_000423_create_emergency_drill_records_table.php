<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_drill_records', function (Blueprint $table) {
            $table->id();
            $table->enum('drill_type', ['fire', 'earthquake', 'evacuation', 'chemical_spill', 'active_shooter']);
            $table->date('drill_date');
            $table->integer('participants_count')->default(0);
            $table->text('assembly_point')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('performance_rating', ['poor', 'fair', 'good', 'excellent']);
            $table->text('lessons_learned')->nullable();
            $table->foreignId('conducted_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_drill_records');
    }
};
