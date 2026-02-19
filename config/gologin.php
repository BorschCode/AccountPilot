<?php

return [
    'api_key' => env('GOLOGIN_API_KEY'),
    'base_url' => env('GOLOGIN_BASE_URL', 'https://api.gologin.com'),

    /*
    |--------------------------------------------------------------------------
    | Risk Score Thresholds
    |--------------------------------------------------------------------------
    | Profiles with risk score below "proceed" are safe to use.
    | Profiles between "proceed" and "manual" are flagged for human review.
    | Profiles above "manual" are marked as flagged and not used.
    */
    'risk_score' => [
        'proceed' => (int) env('GOLOGIN_RISK_SCORE_PROCEED', 30),
        'manual' => (int) env('GOLOGIN_RISK_SCORE_MANUAL', 60),
    ],
];
