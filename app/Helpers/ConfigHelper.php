<?php

if (!function_exists('sys_setting')) {
    /**
     * Get a system setting value
     */
    function sys_setting(string $key, $default = null)
    {
        return app(\App\Services\ConfigurationService::class)->get($key, $default);
    }
}

if (!function_exists('sys_currency')) {
    /**
     * Format a currency amount
     */
    function sys_currency(float $amount): string
    {
        return app(\App\Services\ConfigurationService::class)->formatCurrency($amount);
    }
}

if (!function_exists('sys_date')) {
    /**
     * Format a date using system date format
     */
    function sys_date($date): string
    {
        return app(\App\Services\ConfigurationService::class)->formatDate($date);
    }
}

if (!function_exists('sys_datetime')) {
    /**
     * Format a date and time using system formats
     */
    function sys_datetime($date): string
    {
        return app(\App\Services\ConfigurationService::class)->formatDateTime($date);
    }
}
