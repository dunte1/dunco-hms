<?php

use App\Models\Currency;
use App\Services\CurrencyService;

if (!function_exists('convertCurrency')) {
    /**
     * Convert amount between currencies
     */
    function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $service = app(CurrencyService::class);
        return $service->convertAmount($amount, $fromCurrency, $toCurrency);
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format amount with currency symbol
     */
    function formatCurrency(float $amount, string $currencyCode): string
    {
        $service = app(CurrencyService::class);
        return $service->formatAmount($amount, $currencyCode);
    }
}

if (!function_exists('getBaseCurrency')) {
    /**
     * Get base currency code
     */
    function getBaseCurrency(): string
    {
        $service = app(CurrencyService::class);
        return $service->getBaseCurrencyCode();
    }
}

if (!function_exists('getCurrencyOptions')) {
    /**
     * Get currency options for select dropdowns
     */
    function getCurrencyOptions(): array
    {
        $service = app(CurrencyService::class);
        return $service->getCurrencyOptions();
    }
}

