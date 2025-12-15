<?php
return [

    // Base API details
    'base_url' => env('KEEPGO_BASE_URL', 'https://myaccount.keepgo.com'),

    // Credentials
    'api_key' => env('KEEPGO_API_KEY','b392151d4a405eb6835284b62470300a'),
    'access_token' => env('KEEPGO_ACCESS_TOKEN','AowCRAzDV0VpSyIZQ8/g60DPlvE='),
    'refill_markup_percentage'=> 30,

    // API endpoints
    'endpoints' => [
        'countries' => '/api/v2/countries',
        'regions' => '/api/v2/regions',
        'bundle_details' => '/api/v2/bundles',
        'country_bundles' => '/api/v2/country_bundles',
        'region_bundles' => '/api/v2/region_bundles',
        'global_bundles' => '/api/v2/global_bundles',
        'lifetime_bundles' => '/api/v2/lifetime_bundles',




    ],
];
