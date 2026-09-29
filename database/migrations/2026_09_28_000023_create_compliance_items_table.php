<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('compliance_checklists')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('checklist_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_items');
    }
};
