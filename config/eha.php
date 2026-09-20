<?php

return [

    /*
    |--------------------------------------------------------------------------
    | EHA / SHA Integration
    |--------------------------------------------------------------------------
    | Kenya's Social Health Authority (SHA) is accessed through the Digital
    | Health Agency (DHA) Health Interoperability Gateway (HIE). Credentials
    | are issued per health facility after registration at developer.dha.go.ke.
    |
    | Base URLs:
    |   UAT (test)  : https://ilm-dev.dha.go.ke/uat-middleware/api/v1
    |   Production  : https://ilm.dha.go.ke/api/v1
    |
    | Set EHA_ENV=uat for testing, or EHA_ENV=production for live claims.
    */

    'env' => env('EHA_ENV', 'uat'),

    'base_urls' => [
        'uat' => 'https://ilm-dev.dha.go.ke/uat-middleware/api/v1',
        'production' => 'https://ilm.dha.go.ke/api/v1',
    ],

    'client_id' => env('EHA_CLIENT_ID', ''),

    'client_secret' => env('EHA_CLIENT_SECRET', ''),

    'facility_id' => env('EHA_FACILITY_ID', ''),

    // Only supported value for the HIE is "fr-code"; the X-Facility-Id headers
    // are only sent when EHA_FACILITY_ID is set (a facility-scoped token already
    // carries the facility in its JWT claims and headers would override it).
    'facility_id_type' => env('EHA_FACILITY_ID_TYPE', 'fr-code'),

    'timeout' => (int) env('EHA_TIMEOUT', 30),

    'cache_ttl' => (int) env('EHA_TOKEN_CACHE_TTL', 1700),

    'log_requests' => (bool) env('EHA_LOG_REQUESTS', true),

];
