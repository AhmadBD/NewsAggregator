<?php

return [

    'newsapiorg' => [
        'api_key' => env('NEWSAPIORG_API_KEY'),
    ],
    'guardian' => [
        'api_key' => env('GUARDIAN_API_KEY'),
    ],
    'ny_times' => [
        'api_key' => env('NY_TIMES_API_KEY'),
    ],
    'available_countries' => env('AVAILABLE_COUNTRIES', 'us,en'),
];
