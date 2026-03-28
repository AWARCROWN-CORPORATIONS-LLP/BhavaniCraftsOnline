<?php

return [
    'locales' => [
        'en' => [
            'name' => 'English',
            'currency' => 'USD',
            'symbol' => '$',
            'rate' => 0.012, // Simple rate for now: 1 INR = 0.012 USD
        ],
        'en-in' => [
            'name' => 'India (English)',
            'currency' => 'INR',
            'symbol' => '₹',
            'rate' => 1,
        ],
        'en-us' => [
            'name' => 'United States',
            'currency' => 'USD',
            'symbol' => '$',
            'rate' => 0.012,
        ],
        'en-gb' => [
            'name' => 'United Kingdom',
            'currency' => 'GBP',
            'symbol' => '£',
            'rate' => 0.0095,
        ],
    ],
    'default_locale' => 'en-in',
];
