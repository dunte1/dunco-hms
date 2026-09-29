<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referral_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->unique()->constrained('referrals')->cascadeOnDelete();
            $table->text('treatment_provided');
            $table->text('outcome');
            $table->date('feedback_date');
            $table->foreignId('feedback_by')->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_feedback');
    }
};
