<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('outbreak_investigations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outbreak_event_id')->nullable()->constrained('outbreak_events')->nullOnDelete();
            $table->string('disease_name');
            $table->date('investigation_start_date');
            $table->date('investigation_end_date')->nullable();
            $table->boolean('source_identified')->default(false);
            $table->text('source_description')->nullable();
            $table->text('control_measures');
            $table->string('status')->default('active');
            $table->foreignId('investigated_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbreak_investigations');
    }
};
