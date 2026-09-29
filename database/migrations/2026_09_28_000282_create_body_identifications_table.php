<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('body_identifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortuary_record_id')->constrained('mortuary_records')->cascadeOnDelete();
            $table->string('identifier_name');
            $table->string('identifier_relationship')->nullable();
            $table->enum('identification_method', ['visual', 'photo', 'belongings', 'fingerprint', 'dna']);
            $table->timestamp('identified_at');
            $table->foreignId('identified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_identifications');
    }
};
