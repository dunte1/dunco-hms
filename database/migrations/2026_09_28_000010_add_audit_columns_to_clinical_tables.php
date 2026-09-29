<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'patients',
        'opd_visits',
        'ipd_admissions',
        'prescriptions',
        'prescription_items',
        'lab_requests',
        'lab_request_items',
        'radiology_requests',
        'invoices',
        'invoice_items',
        'payments',
        'referrals',
        'birth_reports',
        'death_reports',
        'operation_reports',
        'patient_diagnoses',
        'consent_forms',
        'documents',
        'mrd_files',
        'vaccination_records',
        'mortuary_records',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn($tableName, 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }
                if (!Schema::hasColumn($tableName, 'facility_id')) {
                    $table->foreignId('facility_id')->nullable()->after('deleted_at')->constrained('hospital_branches')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'facility_id')) {
                    $table->dropForeign(['facility_id']);
                    $table->dropColumn('facility_id');
                }
                if (Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
                if (Schema::hasColumn($tableName, 'updated_by')) {
                    $table->dropForeign(['updated_by']);
                    $table->dropColumn('updated_by');
                }
                if (Schema::hasColumn($tableName, 'created_by')) {
                    $table->dropForeign(['created_by']);
                    $table->dropColumn('created_by');
                }
            });
        }
    }
};
