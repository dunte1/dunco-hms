<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lab_worklists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('department', 30); // haematology, biochemistry, microbiology, immunology, serology, histopathology, parasitology, clinical_microscopy
            $table->string('status', 20)->default('active'); // active, completed
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_worklists');
    }
};
