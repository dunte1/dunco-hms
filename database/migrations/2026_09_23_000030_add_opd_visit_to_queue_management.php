<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('queue_management', function (Blueprint $table) {
            $table->foreignId('opd_visit_id')->nullable()->after('patient_id')->constrained('opd_visits')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('queue_management', function (Blueprint $table) {
            $table->dropForeign(['opd_visit_id']);
            $table->dropColumn('opd_visit_id');
        });
    }
};
