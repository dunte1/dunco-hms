<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sha_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('facility_code')->unique();
            $table->string('county')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('tier_level')->nullable();
            $table->string('accreditation_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('api_base_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->text('certificate_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('sha_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('sha_member_number')->unique();
            $table->string('national_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('tier_level')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('contribution_status')->default('active');
            $table->string('eligibility_status')->default('active');
            $table->decimal('remaining_benefits', 12, 2)->nullable();
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();

            $table->index('national_id');
        });

        Schema::create('sha_authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('authorization_number')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sha_member_id')->constrained()->cascadeOnDelete();
            $table->string('service_type');
            $table->string('service_code')->nullable();
            $table->string('diagnosis_code')->nullable();
            $table->text('diagnosis_description')->nullable();
            $table->decimal('authorized_amount', 12, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'denied', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('authorized_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('api_response')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('sha_service_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->decimal('tariff_amount', 10, 2)->default(0);
            $table->boolean('requires_authorization')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sha_service_codes');
        Schema::dropIfExists('sha_authorizations');
        Schema::dropIfExists('sha_members');
        Schema::dropIfExists('sha_providers');
    }
};
