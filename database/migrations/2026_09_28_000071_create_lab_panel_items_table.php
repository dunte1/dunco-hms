<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lab_panel_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained('lab_panels')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['panel_id', 'lab_test_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_panel_items');
    }
};
