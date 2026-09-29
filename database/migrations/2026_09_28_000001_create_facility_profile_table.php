<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('registration_number')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('country')->default('Kenya');
            $table->string('logo_path')->nullable();
            $table->json('operating_hours')->nullable();
            $table->json('services_offered')->nullable();
            $table->string('level')->nullable()->comment('Kenya hospital level: 1-6');
            $table->string('facility_type')->nullable()->comment('public, private, faith_based, NGO');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_profiles');
    }
};
