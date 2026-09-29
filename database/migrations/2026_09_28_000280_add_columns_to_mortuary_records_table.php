<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mortuary_records', function (Blueprint $table) {
            if (!Schema::hasColumn('mortuary_records', 'body_temperature')) {
                $table->decimal('body_temperature', 5, 1)->nullable()->after('storage_location');
            }
            if (!Schema::hasColumn('mortuary_records', 'condition')) {
                $table->enum('condition', ['normal', 'decomposed', 'injured'])->nullable()->after('body_temperature');
            }
            if (!Schema::hasColumn('mortuary_records', 'slot_number')) {
                $table->string('slot_number')->nullable()->after('condition');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mortuary_records', function (Blueprint $table) {
            $table->dropColumn(['body_temperature', 'condition', 'slot_number']);
        });
    }
};
