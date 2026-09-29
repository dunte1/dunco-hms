<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->nullable()->constrained('payrolls')->nullOnDelete();
            $table->integer('export_month')->unsigned();
            $table->integer('export_year')->unsigned();
            $table->decimal('total_gross', 14, 2);
            $table->decimal('total_deductions', 14, 2);
            $table->decimal('total_net', 14, 2);
            $table->integer('employee_count');
            $table->enum('status', ['draft', 'finalized', 'submitted'])->default('draft');
            $table->foreignId('exported_by')->constrained('users');
            $table->timestamp('exported_at')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_exports');
    }
};
