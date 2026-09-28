<?php

namespace App\Console\Commands;

use App\Models\ActivitySignup;
use App\Models\ActivityVolunteer;
use App\Models\AdminAuditLog;
use App\Models\Child;
use App\Models\Parents;
use App\Models\Supporter;
use App\Models\WorkshopRsvp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

/**
 * Applies the retention periods promised in the Privacy & Data Protection
 * Notice (config/retention.php, docs/privacy-data-inventory.md).
 *
 * Report-only until RETENTION_ENFORCE=true (or --delete): it lists what it
 * would remove and emails that list to the oversight address, so the rules
 * can be checked against real data before anything is deleted. Unfinished
 * registrations are handled separately by app:sweep-pending-registrations.
 */
class ApplyRetention extends Command
{
    protected $signature = 'app:apply-retention
                            {--delete : Delete now, even if RETENTION_ENFORCE is off}
                            {--no-mail : Print the report instead of emailing it}';

    protected $description = 'Delete data past its retention period (report-only unless enforced)';

    /** Order of years, so "end of first year in secondary" can be counted. */
    private const LEVELS = [
        'junior_infants', 'senior_infants', '1st_class', '2nd_class', '3rd_class', '4th_class', '5th_class',
        '6th_class', '1st_year', '2nd_year', '3rd_year', '4th_year', '5th_year', '6th_year',
    ];

    private const FIRST_YEAR = 8;

    public function handle(): int
    {
        $enforce = (bool) config('retention.enforce') || $this->option('delete');
        $lines = [];
        $total = 0;

        foreach ([
            'Children past the end of their first year in secondary' => $this->children(),
            'Parent accounts 12 months after their last child' => $this->parents(),
            'Activity sign-ups 3 months after the activity' => $this->signups(),
            'Activity volunteers 3 months after the activity' => $this->volunteers(),
            'Workshop RSVPs 3 months after the meetings' => $this->rsvps(),
            'Supporters with no contact for 2 years' => $this->supporters(),
            'Admin log entries over 2 years old' => $this->auditLog(),
        ] as $label => [$rows, $describe]) {
            $lines[] = $label.': '.$rows->count();

            foreach ($rows as $row) {
                $lines[] = '  - '.$describe($row);
            }

            $total += $rows->count();

            if ($enforce) {
                $rows->each->delete();
            }
        }

        $unplaced = Child::doesntHave('schoolLink')->count();

        if ($unplaced) {
            $lines[] = '';
            $lines[] = "Note: {$unplaced} ".($unplaced === 1 ? 'child has' : 'children have')
                .' no school and class, so no end date can be worked out; they are kept until a parent adds one or deletes them.';
        }

        $heading = $enforce
            ? "Retention: {$total} ".($total === 1 ? 'record' : 'records').' deleted'
            : "Retention report (nothing deleted): {$total} ".($total === 1 ? 'record' : 'records').' would be deleted';

        if ($enforce && $total > 0) {
            AdminAuditLog::record('retention.apply', null, 'Scheduled retention run', ['deleted' => $total]);
        }

        $body = $heading."\n\n".implode("\n", $lines)
            ."\n\n".($enforce ? '' : "This is a report only. Deletion starts once RETENTION_ENFORCE=true is set.\n");

        if ($this->option('no-mail')) {
            $this->line($body);
        } elseif ($total > 0 || now()->isMonday()) {
            // Daily only when something qualifies; a weekly note otherwise so
            // it is visibly running.
            Mail::raw($body, fn ($m) => $m->to(config('mail.oversight_bcc'))->subject($heading));
        }

        $this->info($heading);

        return self::SUCCESS;
    }

    private function children(): array
    {
        $minimum = now()->subMonths(config('retention.child_minimum_months'));

        $rows = Child::with('schoolLink.currentSchoolClass', 'owner')->get()
            ->filter(function (Child $child) use ($minimum) {
                $end = $this->childEndsAt($child);

                return $end && $end->isPast() && $child->created_at->lt($minimum);
            })
            ->values();

        return [$rows, fn (Child $c) => "#{$c->id} {$c->first_name} ({$c->public_label}), parent "
            .($c->owner?->email ?? 'unknown')];
    }

    /** 30 June at the end of the child's first year in secondary school. */
    private function childEndsAt(Child $child): ?Carbon
    {
        $link = $child->schoolLink;
        $level = array_search($link?->currentSchoolClass?->class_level, self::LEVELS, true);

        if ($level === false) {
            return null;
        }

        // The school year the class was recorded in: September to June.
        $recorded = $link->updated_at ?? $child->created_at;
        $startYear = $recorded->month >= 9 ? $recorded->year : $recorded->year - 1;

        return Carbon::create($startYear + (self::FIRST_YEAR - $level) + 1, 6, 30)->endOfDay();
    }

    private function parents(): array
    {
        $cutoff = now()->subMonths(config('retention.parent_months_after_last_child'));

        $rows = Parents::where('user_type', 'parent')
            ->where(fn ($q) => $q->whereNull('is_admin')->orWhere('is_admin', 0))
            ->whereNotNull('registration_completed_at')
            ->doesntHave('children')
            ->doesntHave('guardianOfChildren')
            ->get()
            ->filter(fn (Parents $p) => ($p->last_child_ended_at ?? $p->onboarded_at ?? $p->created_at)?->lt($cutoff))
            ->values();

        return [$rows, fn (Parents $p) => "#{$p->id} {$p->full_name} <{$p->email}>"];
    }

    private function signups(): array
    {
        $rows = ActivitySignup::with('activity', 'child')
            ->whereHas('activity', fn ($q) => $q->whereNotNull('starts_on')
                ->where('starts_on', '<', now()->subMonths(config('retention.after_event_months'))->toDateString()))
            ->get();

        return [$rows, fn (ActivitySignup $s) => ($s->child?->first_name ?? 'child')." — {$s->activity?->title}"];
    }

    private function volunteers(): array
    {
        $rows = ActivityVolunteer::with('activity')
            ->whereHas('activity', fn ($q) => $q->whereNotNull('starts_on')
                ->where('starts_on', '<', now()->subMonths(config('retention.after_event_months'))->toDateString()))
            ->get();

        return [$rows, fn (ActivityVolunteer $v) => "{$v->name} — {$v->activity?->title}"];
    }

    private function rsvps(): array
    {
        $last = collect(config('notice.workshops', []))->pluck('date')->filter()->max();

        $rows = $last && Carbon::parse($last)->addMonths(config('retention.after_event_months'))->isPast()
            ? WorkshopRsvp::all()
            : new Collection();

        return [$rows, fn (WorkshopRsvp $r) => "{$r->name} <{$r->email}>"];
    }

    private function supporters(): array
    {
        $cutoff = now()->subMonths(config('retention.supporter_idle_months'));

        $rows = Supporter::all()
            ->filter(fn (Supporter $s) => max($s->joined_at, $s->updated_at)?->lt($cutoff))
            ->values();

        return [$rows, fn (Supporter $s) => "#{$s->id} {$s->full_name} <{$s->email}>"];
    }

    private function auditLog(): array
    {
        $rows = AdminAuditLog::where('created_at', '<', now()->subMonths(config('retention.audit_log_months')))->get();

        return [$rows, fn (AdminAuditLog $l) => "#{$l->id} {$l->action} ".$l->created_at?->toDateString()];
    }
}
