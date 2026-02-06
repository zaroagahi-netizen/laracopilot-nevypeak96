<?php

return [

    /*
     * ZARO Honeypot Configuration
     * 
     * Bot ve spam koruması için Spatie Laravel Honeypot
     * Kayıt formlarında otomatik aktif olacak
     */

    'enabled' => env('HONEYPOT_ENABLED', true),

    'name_field_name' => env('HONEYPOT_NAME', 'my_name'),

    'valid_from_field_name' => env('HONEYPOT_VALID_FROM', 'my_time'),

    'amount_of_seconds' => env('HONEYPOT_SECONDS', 3),

    'respond_to_spam_with' => [
        'enabled' => env('HONEYPOT_RESPOND_TO_SPAM', false),
        'redirect' => env('HONEYPOT_REDIRECT_URL'),
        'view' => null,
    ],
];