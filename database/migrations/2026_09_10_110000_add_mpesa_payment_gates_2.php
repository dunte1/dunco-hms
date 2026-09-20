<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('doctor_id')
                ->constrained('payments')->nullOnDelete();
            $table->decimal('consultation_fee', 10, 2)->nullable()->after('payment_id');
        });

        Schema::table('advance_payments', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('patient_id')
                ->constrained('payments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
            $table->dropColumn('consultation_fee');
        });

        Schema::table('advance_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
        });
    }
};