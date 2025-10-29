<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'country' => 'United States',
                'exchange_rate' => 1.000000,
                'is_base_currency' => true,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'United States Dollar - Primary base currency',
                'last_updated' => now(),
            ],
            [
                'code' => 'KES',
                'name' => 'Kenyan Shilling',
                'symbol' => 'KSh',
                'country' => 'Kenya',
                'exchange_rate' => 150.000000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'Kenyan Shilling - Local currency for Kenya',
                'last_updated' => now(),
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'country' => 'European Union',
                'exchange_rate' => 0.850000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'Euro - European Union currency',
                'last_updated' => now(),
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'country' => 'United Kingdom',
                'exchange_rate' => 0.780000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'British Pound Sterling',
                'last_updated' => now(),
            ],
            [
                'code' => 'INR',
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'country' => 'India',
                'exchange_rate' => 83.000000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'Indian Rupee',
                'last_updated' => now(),
            ],
            [
                'code' => 'AED',
                'name' => 'UAE Dirham',
                'symbol' => 'د.إ',
                'country' => 'United Arab Emirates',
                'exchange_rate' => 3.670000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'United Arab Emirates Dirham',
                'last_updated' => now(),
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'country' => 'Japan',
                'exchange_rate' => 150.000000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 0,
                'position' => 'before',
                'description' => 'Japanese Yen - No decimal places',
                'last_updated' => now(),
            ],
            [
                'code' => 'CNY',
                'name' => 'Chinese Yuan',
                'symbol' => '¥',
                'country' => 'China',
                'exchange_rate' => 7.200000,
                'is_base_currency' => false,
                'is_active' => true,
                'decimal_places' => 2,
                'position' => 'before',
                'description' => 'Chinese Yuan Renminbi',
                'last_updated' => now(),
            ],
        ];

        foreach ($currencies as $currencyData) {
            Currency::create($currencyData);
        }
    }
}