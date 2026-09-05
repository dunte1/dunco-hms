<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mortuary_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('death_report_id')->nullable()->constrained('death_reports')->nullOnDelete();
            $table->string('body_id')->unique();
            $table->timestamp('received_at');
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('storage_location')->nullable();
            $table->text('cause_of_death')->nullable();
            $table->enum('status', ['stored', 'released', 'autopsied'])->default('stored');
            $table->string('family_contact_name')->nullable();
            $table->string('family_contact_phone')->nullable();
            $table->text('identification_method')->nullable();
            $table->timestamps();
        });

        Schema::create('mortuary_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortuary_record_id')->constrained('mortuary_records')->cascadeOnDelete();
            $table->string('released_to_name');
            $table->string('released_to_relation')->nullable();
            $table->string('released_to_id_number')->nullable();
            $table->string('released_to_phone')->nullable();
            $table->string('release_authorization_path')->nullable();
            $table->timestamp('released_at');
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('receiving_facility')->nullable();
            $table->string('transport_method')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortuary_releases');
        Schema::dropIfExists('mortuary_records');
    }
};
