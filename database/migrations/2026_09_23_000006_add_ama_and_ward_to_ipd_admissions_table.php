<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ipd_admissions', function (Blueprint $table) {
            $table->text('ama_reason')->nullable()->after('treatment_plan');
            $table->boolean('ama_signed')->default(false)->after('ama_reason');
            $table->foreignId('ward_id')->nullable()->after('bed_id')->constrained('wards')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ipd_admissions', function (Blueprint $table) {
            $table->dropColumn(['ama_reason', 'ama_signed', 'ward_id']);
        });
    }
};
