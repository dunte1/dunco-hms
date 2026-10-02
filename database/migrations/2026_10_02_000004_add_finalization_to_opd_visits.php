<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('opd_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('opd_visits', 'finalized_at')) {
                $table->timestamp('finalized_at')->nullable();
            }
            if (!Schema::hasColumn('opd_visits', 'finalized_by')) {
                $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('opd_visits', function (Blueprint $table) {
            if (Schema::hasColumn('opd_visits', 'finalized_at')) {
                $table->dropColumn('finalized_at');
            }
        });
    }
};
