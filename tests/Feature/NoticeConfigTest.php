<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * The draft invitation named Tuesday 7 October and Thursday 9 October. 7
 * October 2026 is a Wednesday and 9 October is a Friday, so the wording would
 * have sent parents out on the wrong evening. This keeps the labels honest if
 * the dates are ever edited.
 */
class NoticeConfigTest extends TestCase
{
    public function test_each_workshop_label_matches_the_weekday_of_its_date(): void
    {
        $workshops = config('notice.workshops');

        $this->assertNotEmpty($workshops, 'No workshops configured.');

        foreach ($workshops as $key => $workshop) {
            $actual = date('l j F Y', strtotime($workshop['date']));

            $this->assertSame(
                $actual,
                $workshop['label'],
                "Workshop '{$key}' is labelled \"{$workshop['label']}\" but {$workshop['date']} is actually {$actual}."
            );

            $this->assertStringContainsString(
                date('l', strtotime($workshop['date'])),
                $workshop['short'],
                "Workshop '{$key}' short name \"{$workshop['short']}\" names the wrong day."
            );
        }
    }

    public function test_the_notice_has_a_version_so_it_can_be_reissued(): void
    {
        $this->assertNotEmpty(config('notice.version'));
    }
}
