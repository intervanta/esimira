<?php

return [
    'supportedLocales' => [
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
        'ar' => ['name' => 'Arabic', 'script' => 'Arab', 'native' => 'العربية', 'regional' => 'ar_SA'],
        // 'cs' => ['name' => 'Czech', 'script' => 'Latn', 'native' => 'Čeština'],
        // 'de' => ['name' => 'German', 'script' => 'Latn', 'native' => 'Deutsch'],
        // 'es' => ['name' => 'Spanish', 'script' => 'Latn', 'native' => 'Español'],
        // 'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'Français'],
        // 'lt' => ['name' => 'Lithuanian', 'script' => 'Latn', 'native' => 'Lietuvių'],
        // 'ja' => ['name' => 'Japanese', 'script' => 'Jpan', 'native' => '日本語'],
        // 'ko' => ['name' => 'Korean', 'script' => 'Kore', 'native' => '한국어'],
        // 'it' => ['name' => 'Italian', 'script' => 'Latn', 'native' => 'Italiano'],
        // 'pl' => ['name' => 'Polish', 'script' => 'Latn', 'native' => 'Polski'],
        // 'nl' => ['name' => 'Dutch', 'script' => 'Latn', 'native' => 'Nederlands'],
        // 'pt' => ['name' => 'Portuguese', 'script' => 'Latn', 'native' => 'Português'],
        // 'zh' => ['name' => 'Chinese (Simplified)', 'script' => 'Hans', 'native' => '简体中文'],
        // 'zh-tw' => ['name' => 'Chinese (Traditional)', 'script' => 'Hant', 'native' => '繁體中文'],
    ],

    'useAcceptLanguageHeader' => true,
    'hideDefaultLocaleInURL' => false,
    'localesOrder' => ['en', 'es', 'fr', 'de'], // Optional: order languages
];