<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DHA / Digital Health Superhighway Integration
    |--------------------------------------------------------------------------
    | The Digital Health Agency (DHA) Digital Health Superhighway connects
    | hospital systems to national registries: the Client Registry (CR),
    | Facility Registry, and Provider Registry. This layer also provides
    | real-time patient biometric verification and SHA e-claims support.
    |
    | Integration framework (per DHA):
    |   1. Register the health facility with the DHA
    |   2. Obtain secure API credentials
    |   3. Integrate with the Afyalink documentation portal
    |   4. Access services (verify patients, transmit claims)
    |
    | Environment variable reference:
    |   DHA_ENV          : uat or production
    |   DHA_CLIENT_ID    : API client ID
    |   DHA_CLIENT_SECRET: API client secret
    |   DHA_FACILITY_ID  : Facility Registry Number (FRN)
    */

    'env' => env('DHA_ENV', 'uat'),

    'base_urls' => [
        'uat' => env('DHA_UAT_BASE_URL', 'https://api.dha.go.ke/uat/api'),
        'production' => env('DHA_PROD_BASE_URL', 'https://api.dha.go.ke/api'),
    ],

    'client_id' => env('DHA_CLIENT_ID', ''),

    'client_secret' => env('DHA_CLIENT_SECRET', ''),

    'facility_id' => env('DHA_FACILITY_ID', ''),

    'timeout' => (int) env('DHA_TIMEOUT', 30),

    'token_cache_ttl' => (int) env('DHA_TOKEN_CACHE_TTL', 1700),

    'log_requests' => (bool) env('DHA_LOG_REQUESTS', true),

    // Whether clinical documents are transmitted to DHA (in addition to
    // claims). Part of the reimbursement/reconciliation loop.
    'transmit_clinical_documents' => (bool) env('DHA_TRANSMIT_DOCUMENTS', true),
];
