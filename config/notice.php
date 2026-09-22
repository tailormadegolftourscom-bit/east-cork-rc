<?php

return [

    /*
    |--------------------------------------------------------------------------
    | First-visit notice
    |--------------------------------------------------------------------------
    |
    | Shown once per visitor. Raise `version` and everyone sees the notice
    | again, including people who dismissed the previous one — that is the
    | whole point of the version rather than a plain "seen it" flag.
    |
    | Set `enabled` to false to retire the notice without removing anything.
    |
    */

    'enabled' => env('NOTICE_ENABLED', true),

    'version' => env('NOTICE_VERSION', '2026-09-23'),

    'dated' => '23 September 2026',

    /*
    |--------------------------------------------------------------------------
    | Workshops
    |--------------------------------------------------------------------------
    |
    | Keyed by slug because the key ends up in a URL and in the database. Dates
    | are checked against their weekday names in a test — an invitation that
    | names the wrong day sends people on the wrong evening.
    |
    */

    'venue' => 'My Place Midleton',

    'workshops' => [
        'tuesday' => [
            'date' => '2026-10-06',
            'label' => 'Tuesday 6 October 2026',
            'short' => 'Tuesday Workshop',
        ],
        'thursday' => [
            'date' => '2026-10-08',
            'label' => 'Thursday 8 October 2026',
            'short' => 'Thursday Workshop',
        ],
    ],

];
