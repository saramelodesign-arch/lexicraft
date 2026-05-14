<?php

/*
|--------------------------------------------------------------------------
| Supported platform locales (ISO 639-1 primary language tags)
|--------------------------------------------------------------------------
|
| Single source of truth for UI language lists and server-side validation.
| Add entries here first; wire middleware, localized routes, hreflang, and
| persistence in dedicated layers without duplicating locale codes in views.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Fallback locale (URL prefix + app locale when none resolved)
    |--------------------------------------------------------------------------
    */
    'fallback' => 'en',

    'supported' => [

        'en' => [
            'name' => 'English',
            'native' => 'English',
        ],

        'pt' => [
            'name' => 'Portuguese',
            'native' => 'Português',
        ],

        'fr' => [
            'name' => 'French',
            'native' => 'Français',
        ],

        'de' => [
            'name' => 'German',
            'native' => 'Deutsch',
        ],

        'it' => [
            'name' => 'Italian',
            'native' => 'Italiano',
        ],

        'es' => [
            'name' => 'Spanish',
            'native' => 'Español',
        ],

    ],

];
