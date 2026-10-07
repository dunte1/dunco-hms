<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('bed_assignments')) {
            return;
        }

        if (! Schema::hasColumn('bed_assignments', 'nurse_id')) {
            Schema::table('bed_assignments', function (Blueprint $table) {
                $table->foreignId('nurse_id')->nullable()->after('patient_id')
                    ->constrained('nurses')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('bed_assignments', 'status')) {
            Schema::table('bed_assignments', function (Blueprint $table) {
                $table->string('status', 20)->default('active')->after('discharged_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bed_assignments')) {
            if (Schema::hasColumn('bed_assignments', 'nurse_id')) {
                Schema::table('bed_assignments', function (Blueprint $table) {
                    $table->dropConstrainedForeignId('nurse_id');
                });
            }

            if (Schema::hasColumn('bed_assignments', 'status')) {
                Schema::table('bed_assignments', function (Blueprint $table) {
                    $table->dropColumn('status');
                });
            }
        }
    }
};
