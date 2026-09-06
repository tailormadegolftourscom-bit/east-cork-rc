<?php

namespace App\Support;

use Carbon\Carbon;

class ChildMilestones
{
    private const MILESTONES = [
        36 => '3 Years In',
        24 => '2 Years In',
        18 => '18 Months In',
        12 => '1 Year In',
        6 => '6 Months In',
        3 => '3 Months In',
        1 => '1 Month In',
    ];

    public static function badgeFor(Carbon $joinedAt): string
    {
        $monthsIn = $joinedAt->diffInMonths(now());

        foreach (self::MILESTONES as $months => $label) {
            if ($monthsIn >= $months) {
                return $label;
            }
        }

        return 'Just Joined';
    }
}
