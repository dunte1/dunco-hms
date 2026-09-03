<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_providers', function (Blueprint $table) {
            $table->string('website')->nullable()->after('address');
            $table->decimal('coverage_limit', 12, 2)->nullable()->after('coverage_percentage');
            $table->decimal('copayment_percentage', 5, 2)->nullable()->after('coverage_limit');
            $table->decimal('deductible_amount', 10, 2)->nullable()->after('copayment_percentage');
            $table->string('policy_number_prefix', 10)->nullable()->after('deductible_amount');
            $table->text('claim_submission_url')->nullable()->after('policy_number_prefix');
            $table->string('api_endpoint')->nullable()->after('claim_submission_url');
            $table->string('api_key')->nullable()->after('api_endpoint');
            $table->text('notes')->nullable()->after('api_key');
        });

        Schema::table('patient_insurance', function (Blueprint $table) {
            $table->string('group_number')->nullable()->after('policy_number');
            $table->string('policy_holder_name')->nullable()->after('group_number');
            $table->string('policy_holder_relationship')->nullable()->after('policy_holder_name');
            $table->date('coverage_start_date')->nullable()->after('effective_date');
            $table->date('coverage_end_date')->nullable()->after('expiry_date');
            $table->string('coverage_type')->nullable()->after('coverage_amount');
            $table->boolean('is_primary')->default(false)->after('coverage_type');
            $table->decimal('copayment_amount', 10, 2)->nullable()->after('is_primary');
        });
    }

    public function down(): void
    {
        Schema::table('insurance_providers', function (Blueprint $table) {
            $table->dropColumn([
                'website', 'coverage_limit', 'copayment_percentage',
                'deductible_amount', 'policy_number_prefix', 'claim_submission_url',
                'api_endpoint', 'api_key', 'notes',
            ]);
        });

        Schema::table('patient_insurance', function (Blueprint $table) {
            $table->dropColumn([
                'group_number', 'policy_holder_name', 'policy_holder_relationship',
                'coverage_start_date', 'coverage_end_date', 'coverage_type',
                'is_primary', 'copayment_amount',
            ]);
        });
    }
};
