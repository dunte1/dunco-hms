<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anaesthesia_drugs_given', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anaesthesia_record_id')->constrained('anaesthesia_records')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->string('dose');
            $table->enum('route', ['iv', 'im', 'sc', 'inhalation', 'topical']);
            $table->timestamp('time_administered');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anaesthesia_drugs_given');
    }
};
