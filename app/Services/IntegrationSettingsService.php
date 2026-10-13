<?php

namespace App\Services;

use App\Models\IntegrationConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * DB-first integration credentials for M-Pesa, DHA, and SHA/EHA.
 * Values saved in Settings → Integrations override .env for the request lifetime.
 */
class IntegrationSettingsService
{
    public const SLUGS = ['mpesa', 'dha', 'eha-sha'];

    /**
     * Get stored credentials/config for a slug.
     * Falls back to Laravel config() when no active DB row exists.
     */
    public function get(string $slug): array
    {
        $record = IntegrationConfig::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        $db = [];
        if ($record) {
            $db = array_merge(
                is_array($record->credentials ?? null) ? $record->credentials : [],
                is_array($record->config ?? null) ? $record->config : []
            );
        }

        return array_merge($this->envFallback($slug), array_filter($db, fn ($v) => $v !== null && $v !== ''));
    }

    public function isConfigured(string $slug): bool
    {
        $data = $this->get($slug);

        return match ($slug) {
            'mpesa' => filled($data['consumer_key'] ?? null) && filled($data['consumer_secret'] ?? null),
            'dha' => filled($data['client_id'] ?? null) && filled($data['client_secret'] ?? null),
            'eha-sha' => filled($data['client_id'] ?? null) && filled($data['client_secret'] ?? null),
            default => false,
        };
    }

    /**
     * Persist credentials and push them into config() for this request.
     */
    public function put(string $slug, array $data): IntegrationConfig
    {
        $data = array_filter($data, fn ($v) => $v !== null);

        $record = IntegrationConfig::firstOrNew(['slug' => $slug]);
        $record->name = $record->name ?: ucfirst(str_replace(['-', '_'], ' ', $slug));
        $record->type = match ($slug) {
            'mpesa' => 'mpesa',
            'dha' => 'dha',
            'eha-sha' => 'sha',
            default => $record->type ?? 'integration',
        };
        $record->provider = $slug;
        $record->credentials = $this->credentialsFor($slug, $data);
        $record->config = $this->configFor($slug, $data);
        $record->is_active = true;
        $record->is_sandbox = ($data['environment'] ?? config('dha.env', 'uat')) !== 'production'
            && ($data['environment'] ?? config('mpesa.environment', 'sandbox')) !== 'production';
        $record->last_error = null;
        $record->save();

        $this->applyToConfig($slug, $record);

        Cache::forget('dha_access_token_' . ($data['environment'] ?? 'uat'));
        Cache::forget('eha_access_token_' . ($data['environment'] ?? 'uat'));
        Cache::forget('mpesa_access_token');

        return $record;
    }

    public function status(): array
    {
        return [
            'mpesa' => [
                'configured' => $this->isConfigured('mpesa'),
                'environment' => $this->get('mpesa')['environment'] ?? 'sandbox',
                'shortcode' => $this->get('mpesa')['shortcode'] ?? null,
            ],
            'dha' => [
                'configured' => $this->isConfigured('dha'),
                'environment' => $this->get('dha')['env'] ?? 'uat',
                'facility_id' => $this->get('dha')['facility_id'] ?? null,
            ],
            'eha-sha' => [
                'configured' => $this->isConfigured('eha-sha'),
                'environment' => $this->get('eha-sha')['env'] ?? 'uat',
                'facility_id' => $this->get('eha-sha')['facility_id'] ?? null,
            ],
        ];
    }

    protected function credentialsFor(string $slug, array $data): array
    {
        return match ($slug) {
            'mpesa' => array_intersect_key($data, array_flip([
                'consumer_key', 'consumer_secret', 'shortcode', 'passkey', 'business_name',
            ])),
            'dha', 'eha-sha' => array_intersect_key($data, array_flip([
                'client_id', 'client_secret', 'facility_id',
            ])),
            default => [],
        };
    }

    protected function configFor(string $slug, array $data): array
    {
        return match ($slug) {
            'mpesa' => array_intersect_key($data, array_flip([
                'environment', 'callback_url', 'oauth_url', 'stk_push_url', 'query_url',
            ])),
            'dha', 'eha-sha' => array_intersect_key($data, array_flip([
                'env', 'base_url', 'facility_id_type', 'timeout',
            ])),
            default => [],
        };
    }

    protected function envFallback(string $slug): array
    {
        return match ($slug) {
            'mpesa' => [
                'environment' => config('mpesa.environment', 'sandbox'),
                'consumer_key' => config('mpesa.consumer_key'),
                'consumer_secret' => config('mpesa.consumer_secret'),
                'shortcode' => config('mpesa.shortcode'),
                'passkey' => config('mpesa.passkey'),
                'business_name' => config('mpesa.business_name', 'DUNCOHMS'),
                'callback_url' => config('mpesa.callback_url'),
            ],
            'dha' => [
                'env' => config('dha.env', 'uat'),
                'client_id' => config('dha.client_id'),
                'client_secret' => config('dha.client_secret'),
                'facility_id' => config('dha.facility_id'),
                'base_url' => config("dha.base_urls.{$this->envName('dha')}", ''),
            ],
            'eha-sha' => [
                'env' => config('eha.env', 'uat'),
                'client_id' => config('eha.client_id'),
                'client_secret' => config('eha.client_secret'),
                'facility_id' => config('eha.facility_id'),
                'facility_id_type' => config('eha.facility_id_type', 'fr-code'),
                'base_url' => config("eha.base_urls.{$this->envName('eha')}", ''),
            ],
            default => [],
        };
    }

    protected function envName(string $prefix): string
    {
        return config("{$prefix}.env", 'uat');
    }

    protected function applyToConfig(string $slug, IntegrationConfig $record): void
    {
        $creds = is_array($record->credentials ?? null) ? $record->credentials : [];
        $cfg = is_array($record->config ?? null) ? $record->config : [];

        if ($slug === 'mpesa') {
            foreach (['consumer_key', 'consumer_secret', 'shortcode', 'passkey', 'business_name'] as $k) {
                if (filled($creds[$k] ?? null)) {
                    Config::set("mpesa.{$k}", $creds[$k]);
                    Config::set("services.mpesa.{$k}", $creds[$k]);
                }
            }
            if (filled($cfg['environment'] ?? null)) {
                Config::set('mpesa.environment', $cfg['environment']);
                Config::set('services.mpesa.environment', $cfg['environment']);
                $base = $cfg['environment'] === 'production'
                    ? 'https://api.safaricom.co.ke'
                    : 'https://sandbox.safaricom.co.ke';
                Config::set('mpesa.oauth_url', $base . '/oauth/v1/generate?grant_type=client_credentials');
                Config::set('mpesa.stk_push_url', $base . '/mpesa/stkpush/v1/processrequest');
                Config::set('mpesa.query_url', $base . '/mpesa/stkpushquery/v1/query');
                Config::set('services.mpesa.oauth_url', Config::get('mpesa.oauth_url'));
                Config::set('services.mpesa.stk_push_url', Config::get('mpesa.stk_push_url'));
                Config::set('services.mpesa.query_url', Config::get('mpesa.query_url'));
            }
            if (filled($cfg['callback_url'] ?? null)) {
                Config::set('mpesa.callback_url', $cfg['callback_url']);
            }
            return;
        }

        if ($slug === 'dha' || $slug === 'eha-sha') {
            $prefix = $slug === 'dha' ? 'dha' : 'eha';
            foreach (['client_id', 'client_secret', 'facility_id'] as $k) {
                if (filled($creds[$k] ?? null)) {
                    Config::set("{$prefix}.{$k}", $creds[$k]);
                }
            }
            if (filled($cfg['env'] ?? null)) {
                Config::set("{$prefix}.env", $cfg['env']);
            }
            if (filled($cfg['base_url'] ?? null)) {
                $env = Config::get("{$prefix}.env", 'uat');
                Config::set("{$prefix}.base_urls.{$env}", $cfg['base_url']);
            }
            if (filled($cfg['facility_id_type'] ?? null)) {
                Config::set('eha.facility_id_type', $cfg['facility_id_type']);
            }
        }
    }
}
