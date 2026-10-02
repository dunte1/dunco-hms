<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Widen undersized clinical string columns exposed by MySQL strict mode.
 * SQLite was lenient; MySQL rejects values like "3mm reactive" in varchar(10).
 * Uses raw ALTER TABLE (no doctrine/dbal required).
 */
return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE trauma_assessments MODIFY pupils_left VARCHAR(50) NULL');
        DB::statement('ALTER TABLE trauma_assessments MODIFY pupils_right VARCHAR(50) NULL');
        DB::statement('ALTER TABLE icu_admissions MODIFY unit_type VARCHAR(50) NULL');
        DB::statement('ALTER TABLE sedation_scores MODIFY score_type VARCHAR(50) NULL');
        DB::statement('ALTER TABLE pregnancies MODIFY blood_group VARCHAR(20) NULL');
        DB::statement('ALTER TABLE pregnancies MODIFY rh_factor VARCHAR(20) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE trauma_assessments MODIFY pupils_left VARCHAR(10) NULL');
        DB::statement('ALTER TABLE trauma_assessments MODIFY pupils_right VARCHAR(10) NULL');
        DB::statement('ALTER TABLE icu_admissions MODIFY unit_type VARCHAR(10) NULL');
        DB::statement('ALTER TABLE sedation_scores MODIFY score_type VARCHAR(10) NULL');
        DB::statement('ALTER TABLE pregnancies MODIFY blood_group VARCHAR(10) NULL');
        DB::statement('ALTER TABLE pregnancies MODIFY rh_factor VARCHAR(10) NULL');
    }
};
