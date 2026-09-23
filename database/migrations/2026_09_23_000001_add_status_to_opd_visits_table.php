<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('opd_visits', function (Blueprint $table) {
            $table->string('status', 30)->default('registered')->after('visit_type');
            $table->foreignId('triage_id')->nullable()->after('status')->constrained('triages')->nullOnDelete();
            $table->text('triage_notes')->nullable()->after('triage_id');
        });
    }

    public function down(): void
    {
        Schema::table('opd_visits', function (Blueprint $table) {
            $table->dropColumn(['status', 'triage_notes']);
            $table->dropForeign(['triage_id']);
            $table->dropColumn('triage_id');
        });
    }
};
