<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('contract_end_date')->nullable()->after('hire_date');
        });

        Schema::table('opd_visits', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->nullable()->default(0)->after('visit_date');
        });

        Schema::table('queue_management', function (Blueprint $table) {
            $table->string('token_number', 20)->nullable()->after('patient_id');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('contract_end_date');
        });

        Schema::table('opd_visits', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });

        Schema::table('queue_management', function (Blueprint $table) {
            $table->dropColumn('token_number');
        });
    }
};
