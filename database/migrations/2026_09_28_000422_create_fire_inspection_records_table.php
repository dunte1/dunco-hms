<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fire_inspection_records', function (Blueprint $table) {
            $table->id();
            $table->date('inspection_date');
            $table->string('inspector_name');
            $table->integer('equipment_checked')->default(0);
            $table->integer('equipment_passed')->default(0);
            $table->text('deficiencies_found')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->enum('status', ['passed', 'failed', 'conditional']);
            $table->date('next_inspection_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fire_inspection_records');
    }
};
