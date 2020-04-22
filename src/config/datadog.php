<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DataDog API Settings
    |--------------------------------------------------------------------------
    |
    |
    */

    'host' => env('DATADOG_HOST', 'https://api.datadoghq.com/api/v1/'),
    'api_key' => env('DATADOG_KEY', null),
    'driver' => env('DATADOG_DRIVER', 'curl'),
    'SQS_URL' => env('DATADOG_SQS_URL', null),
];
