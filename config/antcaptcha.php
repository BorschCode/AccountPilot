<?php

return [
    'api_key' => env('ANTICAPTCHA_API_KEY'),
    'base_url' => env('ANTICAPTCHA_BASE_URL', 'https://api.anti-captcha.com'),

    /*
    |--------------------------------------------------------------------------
    | Task Polling
    |--------------------------------------------------------------------------
    | How long to wait (seconds) between polling for task results,
    | and the maximum number of attempts before giving up.
    */
    'poll_interval_seconds' => (int) env('ANTICAPTCHA_POLL_INTERVAL', 5),
    'max_poll_attempts' => (int) env('ANTICAPTCHA_MAX_ATTEMPTS', 20),
];
