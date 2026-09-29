<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cssd_return_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_record_id')->constrained('cssd_issue_records')->cascadeOnDelete();
            $table->timestamp('returned_at');
            $table->enum('condition', ['complete', 'damaged', 'missing']);
            $table->text('missing_items')->nullable();
            $table->foreignId('inspected_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cssd_return_records');
    }
};
