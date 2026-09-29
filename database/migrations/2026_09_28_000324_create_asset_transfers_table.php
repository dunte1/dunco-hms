<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->foreignId('from_department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
            $table->foreignId('to_department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
            $table->date('transfer_date');
            $table->text('reason');
            $table->foreignId('transferred_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_transfers');
    }
};
