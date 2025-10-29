<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class ConfigurationService
{
    protected $cacheKey = 'system_settings';
    
    /**
     * Get a system setting
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAll();
        return $settings[$key] ?? $default;
    }
    
    /**
     * Set a system setting
     */
    public function set(string $key, $value): void
    {
        SystemSetting::set($key, $value);
        $this->clearCache();
    }
    
    /**
     * Get all system settings from cache or database
     */
    public function getAll(): array
    {
        return Cache::remember($this->cacheKey, 3600, function () {
            return SystemSetting::pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Clear the settings cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
    
    /**
     * Get application timezone
     */
    public function getTimezone(): string
    {
        return $this->get('timezone', config('app.timezone', 'UTC'));
    }
    
    /**
     * Get currency code
     */
    public function getCurrency(): string
    {
        return $this->get('currency', 'USD');
    }
    
    /**
     * Get currency symbol
     */
    public function getCurrencySymbol(): string
    {
        return $this->get('currency_symbol', '$');
    }
    
    /**
     * Get date format
     */
    public function getDateFormat(): string
    {
        return $this->get('date_format', 'Y-m-d');
    }
    
    /**
     * Get time format
     */
    public function getTimeFormat(): string
    {
        return $this->get('time_format', 'H:i:s');
    }
    
    /**
     * Format currency amount
     */
    public function formatCurrency(float $amount): string
    {
        return $this->getCurrencySymbol() . number_format($amount, 2);
    }
    
    /**
     * Format date
     */
    public function formatDate($date): string
    {
        if (!$date) return '';
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format($this->getDateFormat());
    }
    
    /**
     * Format date and time
     */
    public function formatDateTime($date): string
    {
        if (!$date) return '';
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        $format = $this->getDateFormat() . ' ' . $this->getTimeFormat();
        return $date->format($format);
    }
}
