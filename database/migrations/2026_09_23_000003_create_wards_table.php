<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->string('ward_type', 50)->default('general'); // general, surgical, paediatric, maternity, icu, hdu
            $table->integer('capacity')->default(0);
            $table->foreignId('department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
            $table->foreignId('nurse_in_charge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
