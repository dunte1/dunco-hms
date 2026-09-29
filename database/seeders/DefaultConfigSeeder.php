<?php

namespace Database\Seeders;

use App\Models\FacilityProfile;
use App\Models\NumberSequence;
use App\Models\PaymentMethod;
use App\Models\SystemSetting;
use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class DefaultConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Facility profile
        FacilityProfile::firstOrCreate(['slug' => 'default'], [
            'name' => 'Dunco HMS',
            'registration_number' => 'KEN-HOS-001',
            'phone' => '+254 700 000000',
            'email' => 'info@duncohms.co.ke',
            'address' => 'Hospital Road',
            'city' => 'Nairobi',
            'county' => 'Nairobi',
            'country' => 'Kenya',
            'level' => '6',
            'facility_type' => 'public',
        ]);

        // Number sequences
        $sequences = [
            ['name' => 'patient_mrn', 'prefix' => 'PAT', 'padding' => 6],
            ['name' => 'invoice', 'prefix' => 'INV', 'padding' => 8],
            ['name' => 'lab_order', 'prefix' => 'LAB', 'padding' => 8],
            ['name' => 'radiology_order', 'prefix' => 'RAD', 'padding' => 8],
            ['name' => 'prescription', 'prefix' => 'RX', 'padding' => 8],
            ['name' => 'visit', 'prefix' => 'VIS', 'padding' => 8],
            ['name' => 'admission', 'prefix' => 'ADM', 'padding' => 8],
            ['name' => 'referral_in', 'prefix' => 'REF-IN', 'padding' => 6],
            ['name' => 'referral_out', 'prefix' => 'REF-OUT', 'padding' => 6],
            ['name' => 'receipt', 'prefix' => 'RCP', 'padding' => 8],
        ];

        foreach ($sequences as $seq) {
            NumberSequence::firstOrCreate(
                ['name' => $seq['name']],
                $seq + ['next_number' => 1, 'is_active' => true]
            );
        }

        // Tax rates
        TaxRate::firstOrCreate(['code' => 'VAT'], [
            'name' => 'VAT 16%',
            'rate' => 16.00,
            'description' => 'Kenya Value Added Tax',
            'is_active' => true,
            'is_inclusive' => false,
        ]);

        TaxRate::firstOrCreate(['code' => 'EXEMPT'], [
            'name' => 'Tax Exempt',
            'rate' => 0.00,
            'description' => 'No tax applied',
            'is_active' => true,
            'is_inclusive' => false,
        ]);

        // Payment methods
        $methods = [
            ['name' => 'Cash', 'code' => 'CASH', 'type' => 'cash'],
            ['name' => 'M-Pesa', 'code' => 'MPESA', 'type' => 'mobile_money'],
            ['name' => 'Card', 'code' => 'CARD', 'type' => 'card'],
            ['name' => 'Bank Transfer', 'code' => 'BANK', 'type' => 'bank_transfer'],
            ['name' => 'Insurance', 'code' => 'INS', 'type' => 'insurance'],
            ['name' => 'Credit', 'code' => 'CREDIT', 'type' => 'credit'],
        ];

        foreach ($methods as $i => $method) {
            PaymentMethod::firstOrCreate(
                ['code' => $method['code']],
                $method + ['is_active' => true, 'sort_order' => $i]
            );
        }

        // Emergency contacts
        SystemSetting::set('emergency_phone_1', '+254 700 000 000', 'string', 'Primary emergency phone number', true);
        SystemSetting::set('emergency_phone_2', '+254 700 000 001', 'string', 'Secondary emergency phone number', true);
        SystemSetting::set('emergency_email', 'emergency@duncohms.co.ke', 'string', 'Emergency email address', true);
        SystemSetting::set('ambulance_phone', '+254 700 000 002', 'string', 'Ambulance dispatch phone number', true);
        SystemSetting::set('emergency_department_phone', '+254 700 000 003', 'string', 'Emergency department direct line', true);
        SystemSetting::set('hospital_phone', '+254 700 000 000', 'string', 'Main hospital phone number', true);
        SystemSetting::set('hospital_name', 'Dunco HMS', 'string', 'Hospital name for display', true);
    }
}
