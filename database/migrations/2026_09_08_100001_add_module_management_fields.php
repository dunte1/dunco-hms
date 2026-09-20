<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('modules', 'name')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('modules', 'slug')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
                $table->unique('slug');
            });
        }
        if (!Schema::hasColumn('modules', 'category')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->string('category')->nullable()->after('slug');
            });
        }
        if (!Schema::hasColumn('modules', 'is_enabled')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->boolean('is_enabled')->default(true)->after('category');
            });
        }
        if (!Schema::hasColumn('modules', 'sort_order')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_enabled');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('modules', 'name')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn(['name', 'slug', 'category', 'is_enabled', 'sort_order']);
            });
        }
    }
};
