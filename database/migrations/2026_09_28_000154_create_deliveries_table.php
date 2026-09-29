<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('labour_record_id')->nullable()->constrained('labour_records')->nullOnDelete();
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->enum('mode', ['normal_assisted', 'vacuum', 'forceps', 'caesarean', 'episiotomy']);
            $table->enum('baby_sex', ['male', 'female']);
            $table->integer('birth_weight_grams');
            $table->integer('apgar_1_min')->nullable();
            $table->integer('apgar_5_min')->nullable();
            $table->boolean('alive')->default(true);
            $table->text('complications')->nullable();
            $table->dateTime('placenta_delivered_time')->nullable();
            $table->boolean('placenta_complete')->nullable();
            $table->integer('blood_loss_ml')->nullable();
            $table->foreignId('delivered_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
