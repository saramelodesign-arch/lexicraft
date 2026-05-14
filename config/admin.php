<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bootstrap admin access by email
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of verified user emails that may access /admin
    | in addition to users with is_admin = true. Useful before the first
    | admin flag is set in the database.
    |
    */
    'allow_emails' => array_values(array_filter(array_map(
        static fn (string $e): string => strtolower(trim($e)),
        explode(',', (string) env('ADMIN_EMAILS', '')),
    ))),

];
