<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Data retention
    |--------------------------------------------------------------------------
    |
    | The periods promised in the Privacy & Data Protection Notice, applied by
    | `php artisan app:apply-retention`. See docs/privacy-data-inventory.md.
    |
    | `enforce` starts false: the job then only reports what it would delete,
    | by email to the oversight address, so the rules can be checked against
    | real data before anything is removed. Set RETENTION_ENFORCE=true once
    | the reports have been reviewed.
    |
    */

    'enforce' => env('RETENTION_ENFORCE', false),

    // Children: until the end of their first year in secondary school, and
    // never less than this long after registration (so a child registered in
    // 2nd Year is not removed the moment they join).
    'child_minimum_months' => 12,

    // Parents: this long after their last child's record ends (or after
    // joining, if they never registered a child).
    'parent_months_after_last_child' => 12,

    // Activity sign-ups and volunteers, and workshop RSVPs: this long after
    // the date.
    'after_event_months' => 3,

    // Supporters: this long with no contact or change.
    'supporter_idle_months' => 24,

    // Admin audit log entries.
    'audit_log_months' => 24,

];
