<?php

$env = env('MPESA_ENVIRONMENT', 'sandbox');
$base = $env === 'production'
    ? 'https://api.safaricom.co.ke'
    : 'https://sandbox.safaricom.co.ke';

return [

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Integration (Safaricom Daraja API)
    |--------------------------------------------------------------------------
    |
    | Set MPESA_ENVIRONMENT to 'sandbox' for testing or 'production' for live.
    | The API URLs are resolved automatically based on this setting.
    |
    */

    'environment' => $env,

    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'shortcode' => env('MPESA_SHORTCODE'),
    'passkey' => env('MPESA_PASSKEY'),
    'business_name' => env('MPESA_BUSINESS_NAME', 'DUNCOHMS'),

    'oauth_url' => env('MPESA_OAUTH_URL', $base . '/oauth/v1/generate?grant_type=client_credentials'),
    'stk_push_url' => env('MPESA_STK_PUSH_URL', $base . '/mpesa/stkpush/v1/processrequest'),
    'query_url' => env('MPESA_QUERY_URL', $base . '/mpesa/stkpushquery/v1/query'),

    'callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL', 'http://127.0.0.1:8001') . '/api/mpesa/callback'),
    'result_url' => env('MPESA_RESULT_URL', env('APP_URL', 'http://127.0.0.1:8001') . '/api/mpesa/result'),
    'confirmation_url' => env('MPESA_CONFIRMATION_URL', env('APP_URL', 'http://127.0.0.1:8001') . '/api/mpesa/confirmation'),
    'validation_url' => env('MPESA_VALIDATION_URL', env('APP_URL', 'http://127.0.0.1:8001') . '/api/mpesa/validation'),

    /*
    |--------------------------------------------------------------------------
    | Fee Types resolvable to an M-Pesa payment path
    |--------------------------------------------------------------------------
    | These are the payable service types recognised across the HMIS. Each one
    | maps to a display label and (optionally) a SHA service code used for
    | coverage checks. Any payable service listed in the audit must appear here.
    */
    'fee_types' => [
        'registration_fee' => 'Registration Fee',
        'consultation_fee' => 'Consultation Fee',
        'lab_test' => 'Lab Test Fee',
        'pharmacy' => 'Pharmacy / Prescription Fee',
        'admission_fee' => 'Admission Fee',
        'deposit' => 'Deposit',
        'invoice_payment' => 'Invoice Payment',
    ],

    /*
    |--------------------------------------------------------------------------
    | SHA coverage mapping
    |--------------------------------------------------------------------------
    | Maps each fee type to the SHA service codes that cover it. A patient
    | whose SHA membership is active AND matches an active service code below
    | is treated as SHA-covered for that service (self-pay / M-Pesa is still
    | offered as the out-of-pocket path). Entries in the sha_service_codes
    | table with a matching fee_type column override these defaults.
    */
    'sha_service_codes' => [
        'registration_fee' => [
            ['code' => 'REGSVC', 'name' => 'Registration'],
        ],
        'consultation_fee' => [
            ['code' => 'CONSULT', 'name' => 'Outpatient Consultation'],
        ],
        'lab_test' => [
            ['code' => 'LABTST', 'name' => 'Laboratory Tests'],
        ],
        'pharmacy' => [
            ['code' => 'MDSPEC', 'name' => 'Medicines / Pharmacy'],
        ],
        'admission_fee' => [
            ['code' => 'INPAT', 'name' => 'Inpatient Admission'],
        ],
    ],

    // ResultCode values from Daraja STK push callbacks -> internal status.
    'result_code_map' => [
        1032 => 'timeout',
        1037 => 'cancelled',
        1031 => 'failed',
        1034 => 'failed',
        1036 => 'failed',
        1039 => 'failed',
        2001 => 'failed',
    ],

];
