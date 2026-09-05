<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'status', 'phone', 'bio', 'profile_photo_path',
                'approved_by', 'approved_at', 'status_notes'
            ];
            foreach ($columns as $column) {
                if (!Schema::hasColumn('users', $column)) {
                    match($column) {
                        'status' => $table->string('status')->default('pending')->after('password'),
                        'phone' => $table->string('phone')->nullable()->after('status'),
                        'bio' => $table->text('bio')->nullable()->after('phone'),
                        'profile_photo_path' => $table->string('profile_photo_path')->nullable()->after('bio'),
                        'approved_by' => $table->foreignId('approved_by')->nullable()->after('profile_photo_path'),
                        'approved_at' => $table->timestamp('approved_at')->nullable()->after('approved_by'),
                        'status_notes' => $table->text('status_notes')->nullable()->after('approved_at'),
                        default => null,
                    };
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'phone', 'bio', 'profile_photo_path', 'approved_by', 'approved_at', 'status_notes']);
        });
    }
};
