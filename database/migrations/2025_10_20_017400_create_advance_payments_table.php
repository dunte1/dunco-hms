<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50);
            $table->string('payment_reference')->nullable();
            $table->date('payment_date');
            $table->text('purpose')->nullable();
            $table->decimal('used_amount', 10, 2)->default(0);
            $table->decimal('balance_amount', 10, 2);
            $table->string('status', 20)->default('active'); // active, used, refunded, expired
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
    }
};
