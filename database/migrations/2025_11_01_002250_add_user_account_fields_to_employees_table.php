<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Link to user account
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            
            // Additional personal information
            $table->string('nationality')->nullable()->after('address');
            $table->string('id_number')->nullable()->after('nationality');
            
            // Bank information
            $table->string('bank_name')->nullable()->after('id_number');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('bank_branch')->nullable()->after('account_number');
            
            // Next of kin
            $table->string('next_of_kin_name')->nullable()->after('bank_branch');
            $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_name');
            $table->string('next_of_kin_contact')->nullable()->after('next_of_kin_relationship');
            
            // Supervisor/Manager
            $table->foreignId('supervisor_id')->nullable()->after('next_of_kin_contact')->constrained('employees')->nullOnDelete();
            
            // Contract details
            $table->string('contract_type')->nullable()->after('employment_type'); // permanent, contract, temporary
            $table->date('contract_start_date')->nullable()->after('contract_type');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            
            // Photo field should already exist from previous migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn([
                'user_id',
                'nationality',
                'id_number',
                'bank_name',
                'account_number',
                'bank_branch',
                'next_of_kin_name',
                'next_of_kin_relationship',
                'next_of_kin_contact',
                'supervisor_id',
                'contract_type',
                'contract_start_date',
                'contract_end_date'
            ]);
        });
    }
};
