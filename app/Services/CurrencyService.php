<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * Get exchange rates from external API
     */
    public function fetchExchangeRates(string $baseCurrency = 'USD'): array
    {
        try {
            // Using exchangerate-api.com (free tier)
            $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$baseCurrency}");
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['rates'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rates: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Update exchange rates for all currencies
     */
    public function updateAllExchangeRates(): bool
    {
        $baseCurrency = Currency::getBaseCurrency();
        if (!$baseCurrency) {
            return false;
        }

        $rates = $this->fetchExchangeRates($baseCurrency->code);
        if (empty($rates)) {
            return false;
        }

        $currencies = Currency::where('is_active', true)->get();
        
        foreach ($currencies as $currency) {
            if ($currency->is_base_currency) {
                continue;
            }

            if (isset($rates[$currency->code])) {
                $currency->updateExchangeRate($rates[$currency->code]);
            }
        }

        return true;
    }

    /**
     * Convert amount between currencies
     */
    public function convertAmount(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $fromCurrencyModel = Currency::where('code', $fromCurrency)->first();
        $toCurrencyModel = Currency::where('code', $toCurrency)->first();

        if (!$fromCurrencyModel || !$toCurrencyModel) {
            return $amount;
        }

        return $fromCurrencyModel->convertTo($amount, $toCurrency);
    }

    /**
     * Format amount with currency
     */
    public function formatAmount(float $amount, string $currencyCode): string
    {
        $currency = Currency::where('code', $currencyCode)->first();
        
        if (!$currency) {
            return number_format($amount, 2);
        }

        return $currency->formatAmount($amount);
    }

    /**
     * Get currency options for select dropdowns
     */
    public function getCurrencyOptions(): array
    {
        return Currency::active()
            ->orderBy('name')
            ->pluck('name', 'code')
            ->toArray();
    }

    /**
     * Get base currency code
     */
    public function getBaseCurrencyCode(): string
    {
        $baseCurrency = Currency::getBaseCurrency();
        return $baseCurrency ? $baseCurrency->code : 'USD';
    }

    /**
     * Check if currency code exists
     */
    public function currencyExists(string $code): bool
    {
        return Currency::where('code', $code)->exists();
    }

    /**
     * Get currency by code
     */
    public function getCurrencyByCode(string $code): ?Currency
    {
        return Currency::where('code', $code)->first();
    }
}

