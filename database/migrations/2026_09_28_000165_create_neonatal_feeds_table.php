<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('neonatal_feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newborn_id')->constrained('newborns')->cascadeOnDelete();
            $table->dateTime('feed_time');
            $table->enum('feed_type', ['breast', 'formula', 'tpn', 'expressed']);
            $table->decimal('volume_ml', 6, 1)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('method', ['suckling', 'cup', 'spoon', 'tube']);
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('neonatal_feeds');
    }
};
