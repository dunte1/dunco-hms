<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linen_records', function (Blueprint $table) {
            $table->id();
            $table->enum('linen_type', ['bed_sheets', 'pillows', 'gowns', 'towels', 'other']);
            $table->integer('quantity');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->enum('status', ['clean', 'soiled', 'processing', 'lost'])->default('clean');
            $table->date('issue_date')->nullable();
            $table->date('return_date')->nullable();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linen_records');
    }
};
