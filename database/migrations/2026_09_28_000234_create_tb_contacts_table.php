<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tb_case_id')->constrained('tb_cases')->cascadeOnDelete();
            $table->string('contact_name');
            $table->string('contact_phone')->nullable();
            $table->string('contact_relationship');
            $table->boolean('screened')->default(false);
            $table->string('screening_result', 20)->default('pending');
            $table->foreignId('hts_encounter_id')->nullable();
            $table->date('screened_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_contacts');
    }
};
