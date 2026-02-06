<?php

return [
    'supportedLocales' => [
        'tr' => ['name' => 'Türkçe', 'script' => 'Latn', 'native' => 'Türkçe', 'regional' => 'tr_TR'],
        'ku-latn' => ['name' => 'Kurdish (Latin)', 'script' => 'Latn', 'native' => 'Kurdî (Latînî)', 'regional' => 'ku_TR'],
        'ku-arab' => ['name' => 'Kurdish (Sorani)', 'script' => 'Arab', 'native' => 'کوردی (سۆرانی)', 'regional' => 'ku_IQ', 'dir' => 'rtl'],
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
        'de' => ['name' => 'German', 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_DE'],
        'ar' => ['name' => 'Arabic', 'script' => 'Arab', 'native' => 'العربية', 'regional' => 'ar_SA', 'dir' => 'rtl'],
        'fa' => ['name' => 'Persian', 'script' => 'Arab', 'native' => 'فارسی', 'regional' => 'fa_IR', 'dir' => 'rtl'],
    ],

    'useAcceptLanguageHeader' => true,
    'hideDefaultLocaleInURL' => false,
    'localesOrder' => ['tr', 'ku-latn', 'ku-arab', 'en', 'de', 'ar', 'fa'],
    'localesMapping' => [],
    'utf8suffix' => '.UTF-8',
    'urlsIgnored' => ['/admin', '/filament'],
];