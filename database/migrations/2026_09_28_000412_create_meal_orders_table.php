<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'supplement']);
            $table->enum('diet_type', ['regular', 'soft', 'liquid', 'NPO', 'diabetic', 'cardiac', 'renal']);
            $table->integer('quantity')->default(1);
            $table->date('order_date');
            $table->time('order_time');
            $table->enum('status', ['ordered', 'prepared', 'delivered', 'cancelled'])->default('ordered');
            $table->foreignId('ordered_by')->constrained('users');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_orders');
    }
};
