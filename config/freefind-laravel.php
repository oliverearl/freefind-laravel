<?php

declare(strict_types=1);

// Configuration for FreeFind's Page Search integration.
return [
    'site_id' => env('FREEFIND_SITE_ID'),

    'endpoints' => [
        'html' => 'https://search.freefind.com/find.html',
        'xml' => 'https://search.freefind.com/find.xml',
        'index' => 'https://search.freefind.com/siteindex.html',
    ],

    'http' => [
        'connect_timeout' => 2,
        'timeout' => 5,
        'max_response_bytes' => 2_000_000,
    ],

    'spider' => [
        'middleware' => false,
        'user_agents' => ['freefind/2.1'],
        'cache_control' => 'public, max-age=3600',
    ],
];
