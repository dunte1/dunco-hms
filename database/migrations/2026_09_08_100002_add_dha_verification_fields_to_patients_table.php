<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('patients', 'national_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('national_id')->nullable()->after('patient_no');
            });
        }
        if (!Schema::hasColumn('patients', 'dha_cr_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('dha_cr_id')->nullable()->after('national_id');
            });
        }
        if (!Schema::hasColumn('patients', 'dha_verified_at')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->timestamp('dha_verified_at')->nullable()->after('dha_cr_id');
            });
        }
    }

    public function down(): void
    {
        foreach (['national_id', 'dha_cr_id', 'dha_verified_at'] as $col) {
            if (Schema::hasColumn('patients', $col)) {
                Schema::table('patients', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};