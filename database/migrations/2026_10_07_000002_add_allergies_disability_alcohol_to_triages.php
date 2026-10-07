<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            if (! Schema::hasColumn('triages', 'allergies')) {
                $table->text('allergies')->nullable()->after('triage_notes');
            }
            if (! Schema::hasColumn('triages', 'disability')) {
                $table->boolean('disability')->default(false)->after('allergies');
            }
            if (! Schema::hasColumn('triages', 'disability_notes')) {
                $table->text('disability_notes')->nullable()->after('disability');
            }
            if (! Schema::hasColumn('triages', 'alcohol_use')) {
                $table->enum('alcohol_use', ['never', 'occasionally', 'regularly', 'heavy', 'unknown'])->default('unknown')->after('disability_notes');
            }
            if (! Schema::hasColumn('triages', 'alcohol_notes')) {
                $table->text('alcohol_notes')->nullable()->after('alcohol_use');
            }
        });
    }

    public function down(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            foreach (['allergies', 'disability', 'disability_notes', 'alcohol_use', 'alcohol_notes'] as $col) {
                if (Schema::hasColumn('triages', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
