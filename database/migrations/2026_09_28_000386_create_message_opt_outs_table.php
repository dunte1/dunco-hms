<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('message_opt_outs', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('channel', 20); // sms, email, whatsapp
            $table->text('reason')->nullable();
            $table->dateTime('opted_out_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_opt_outs');
    }
};
