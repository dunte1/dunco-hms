<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->text('digital_signature')->nullable()->after('notes');
            $table->timestamp('signed_at')->nullable()->after('digital_signature');
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete()->after('signed_at');
            $table->string('template_id')->nullable()->after('signed_by');
            $table->json('metadata')->nullable()->after('template_id'); // Additional prescription metadata
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['digital_signature', 'signed_at', 'signed_by', 'template_id', 'metadata']);
        });
    }
};
