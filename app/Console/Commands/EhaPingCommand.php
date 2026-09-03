<?php

namespace App\Console\Commands;

use App\Services\ShaService;
use Illuminate\Console\Command;

class EhaPingCommand extends Command
{
    protected $signature = 'eha:ping {--search= : National ID / member number to test patient search with}';

    protected $description = 'Diagnose the EHA/SHA integration (config, connectivity, auth)';

    public function handle(ShaService $sha): int
    {
        $this->info('EHA/SHA integration diagnostic');
        $this->newLine();

        $this->line('Environment      : ' . config('eha.env', 'uat'));
        $this->line('Base URL         : ' . $sha->baseUrl());
        $this->line('Client ID        : ' . (config('eha.client_id') ? '****' : '(empty)'));
        $this->line('Client Secret    : ' . (config('eha.client_secret') ? '****' : '(empty)'));
        $this->line('Facility ID      : ' . (config('eha.facility_id') ?: '(empty)'));
        $this->line('Configured       : ' . ($sha->isConfigured() ? 'yes' : 'no'));

        $this->newLine();

        if (!$sha->isConfigured()) {
            $this->warn('EHA is NOT configured. Add EHA_ENV, EHA_CLIENT_ID, EHA_CLIENT_SECRET, EHA_FACILITY_ID to .env.');
            return self::SUCCESS;
        }

        $this->line('Requesting access token...');
        $token = $sha->fetchAccessToken();

        if (!$token) {
            $this->error('Failed to obtain access token. Check credentials / base URL.');
            return self::FAILURE;
        }

        $this->info('Access token obtained (cached).');

        $search = $this->option('search');
        if ($search) {
            $this->newLine();
            $this->line("Testing patient search with identification_number={$search} ...");
            $result = $sha->searchPatient(['identification_number' => $search, 'identification_type' => 'ID']);
            $this->table(
                ['key', 'value'],
                [
                    ['success', $result['success'] ? 'yes' : 'no'],
                    ['http status', (string) $result['status']],
                    ['code', (string) $result['code']],
                    ['message', (string) $result['message']],
                ]
            );
        }

        $this->newLine();
        $this->info('Diagnostic complete. Any failures were audited to insurance_api_logs.');

        return self::SUCCESS;
    }
}
