<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->text('item_description');
            $table->string('location_found');
            $table->date('date_found');
            $table->foreignId('found_by')->constrained('users');
            $table->string('claimed_by_name')->nullable();
            $table->string('claimed_by_id_number')->nullable();
            $table->datetime('claimed_at')->nullable();
            $table->string('status', 20)->default('unclaimed'); // unclaimed, claimed, disposed
            $table->string('storage_location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_items');
    }
};
