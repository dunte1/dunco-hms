<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('birth_reports', function (Blueprint $table) {
            $table->foreignId('mother_patient_id')->nullable()->after('mother_name')->constrained('patients')->nullOnDelete();
            $table->foreignId('baby_patient_id')->nullable()->after('baby_name')->constrained('patients')->nullOnDelete();
            $table->foreignId('ipd_admission_id')->nullable()->after('attending_nurse_id')->constrained('ipd_admissions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('birth_reports', function (Blueprint $table) {
            $table->dropForeign(['mother_patient_id', 'baby_patient_id', 'ipd_admission_id']);
            $table->dropColumn(['mother_patient_id', 'baby_patient_id', 'ipd_admission_id']);
        });
    }
};
