<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hai_surveillance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('infection_type');
            $table->string('organism')->nullable();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->date('onset_date');
            $table->date('reported_date');
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('suspected');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hai_surveillance_records');
    }
};
