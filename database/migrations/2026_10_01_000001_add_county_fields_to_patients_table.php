<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('nationality')->default('Kenyan')->after('national_id');
            $table->string('county')->nullable()->after('nationality');
            $table->string('sub_county')->nullable()->after('county');
            $table->string('ward')->nullable()->after('sub_county');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['nationality', 'county', 'sub_county', 'ward']);
        });
    }
};
