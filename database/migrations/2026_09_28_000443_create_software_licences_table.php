<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('software_licences', function (Blueprint $table) {
            $table->id();
            $table->string('software_name');
            $table->text('licence_key')->nullable();
            $table->enum('licence_type', ['single', 'concurrent', 'site', 'subscription']);
            $table->integer('max_seats')->default(1);
            $table->integer('current_seats')->default(0);
            $table->date('purchase_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->enum('status', ['active', 'expired', 'unused'])->default('unused');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('software_licences');
    }
};
