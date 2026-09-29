<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('source_module')->nullable();
            $table->text('query_sql')->nullable();
            $table->decimal('target_value', 12, 2)->nullable();
            $table->string('unit')->nullable();
            $table->string('comparison_period')->default('monthly'); // daily, weekly, monthly, yearly
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_definitions');
    }
};
