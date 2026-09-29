<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->comment('invoice, receipt, discharge_summary, lab_report, prescription, referral, certificate, claim');
            $table->text('subject')->nullable();
            $table->longText('body')->comment('Blade template content');
            $table->string('format')->default('pdf')->comment('pdf, html, docx');
            $table->json('variables')->nullable()->comment('Available template variables');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event')->comment('e.g. appointment_reminder, payment_received, lab_result_ready');
            $table->string('channel')->comment('sms, email, whatsapp, push');
            $table->string('subject')->nullable()->comment('For email');
            $table->longText('body');
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['event', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('document_templates');
    }
};
