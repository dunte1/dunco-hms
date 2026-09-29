<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainee_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 200);
            $table->enum('trainee_type', ['student', 'intern', 'resident', 'fellow']);
            $table->string('institution', 200)->nullable();
            $table->foreignId('department_id')->constrained('employee_departments');
            $table->string('program_name', 150)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 30)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_records');
    }
};
