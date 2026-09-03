<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_claims', function (Blueprint $table) {
            $table->string('sha_authorization_number')->nullable()->after('insurance_reference');
            $table->json('sha_service_codes')->nullable()->after('sha_authorization_number');
            $table->decimal('sha_tariff_amount', 12, 2)->nullable()->after('sha_service_codes');
            $table->decimal('sha_patient_amount', 12, 2)->nullable()->after('sha_tariff_amount');
            $table->json('submission_response')->nullable()->after('sha_patient_amount');
            $table->date('remittance_date')->nullable()->after('submission_response');
            $table->string('remittance_reference')->nullable()->after('remittance_date');
        });

        Schema::create('icd10_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description');
            $table->string('category')->nullable();
            $table->boolean('is_chapter_heading')->default(false);
            $table->string('parent_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('category');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icd10_codes');
        Schema::table('insurance_claims', function (Blueprint $table) {
            $table->dropColumn([
                'sha_authorization_number', 'sha_service_codes', 'sha_tariff_amount',
                'sha_patient_amount', 'submission_response', 'remittance_date', 'remittance_reference',
            ]);
        });
    }
};
