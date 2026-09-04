<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('password');
            $table->string('phone')->nullable()->after('status');
            $table->text('bio')->nullable()->after('phone');
            $table->string('profile_photo_path')->nullable()->after('bio');
            $table->foreignId('approved_by')->nullable()->after('profile_photo_path');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('status_notes')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'phone', 'bio', 'profile_photo_path', 'approved_by', 'approved_at', 'status_notes']);
        });
    }
};
