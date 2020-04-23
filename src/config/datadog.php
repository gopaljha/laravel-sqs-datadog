<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DataDog API Settings
    |--------------------------------------------------------------------------
    |
    |
    */

    'HOST' => env('DATADOG_HOST', 'https://api.datadoghq.com/api/v1/'),
    'API_KEY' => env('DATADOG_KEY', null),
    'DRIVER' => env('DATADOG_DRIVER', null),
    'SQS_URL' => env('DATADOG_SQS_URL', null),
    'LOG_ENDPOINT' => env('DATADOG_LOG_ENDPOINT', 'https://http-intake.logs.datadoghq.com/v1/input/'),
];
