<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_group_id')->constrained('blood_groups')->cascadeOnDelete();
            $table->foreignId('donor_id')->nullable()->constrained('blood_donors')->nullOnDelete();
            $table->string('bag_number')->unique();
            $table->date('collection_date');
            $table->date('expiry_date');
            $table->string('status', 20)->default('available'); // available, used, expired, discarded
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_inventory');
    }
};
