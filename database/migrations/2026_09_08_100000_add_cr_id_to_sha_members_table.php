<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sha_members', function (Blueprint $table) {
            $table->string('cr_id')->nullable()->after('national_id');
            $table->index('cr_id');
        });
    }

    public function down(): void
    {
        Schema::table('sha_members', function (Blueprint $table) {
            $table->dropIndex(['cr_id']);
            $table->dropColumn('cr_id');
        });
    }
};
