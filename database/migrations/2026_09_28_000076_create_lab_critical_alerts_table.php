<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lab_critical_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_request_item_id')->constrained('lab_request_items')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('alert_type', 20); // critical_high, critical_low, critical_value
            $table->string('result_value')->nullable();
            $table->string('reference_range')->nullable();
            $table->text('message');
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('acknowledged_at')->nullable();
            $table->boolean('is_acknowledged')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_critical_alerts');
    }
};
