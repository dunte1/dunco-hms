<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Annual, Sick, Maternity, etc.
            $table->text('description')->nullable();
            $table->integer('default_days')->default(0); // Default days allocated per year
            $table->boolean('carry_forward')->default(false); // Can be carried forward
            $table->boolean('requires_approval')->default(true);
            $table->string('color', 7)->default('#3B82F6'); // For UI display
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
