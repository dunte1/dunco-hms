<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('in_app_alerts')) {
            Schema::create('in_app_alerts', function (Blueprint $table) {
                $table->id();
                $table->string('type', 50)->index();
                $table->string('title');
                $table->text('body')->nullable();
                $table->string('target_role', 50)->nullable()->index();
                $table->unsignedBigInteger('related_id')->nullable();
                $table->string('related_type', 100)->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('in_app_alerts');
    }
};
