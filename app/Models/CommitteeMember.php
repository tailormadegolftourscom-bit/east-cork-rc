<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One person's place on one committee.
 *
 * Polymorphic because committees hold parents and non-parent supporters
 * alike, and those are separate tables on purpose.
 */
class CommitteeMember extends Model
{
    protected $table = 'committee_members';

    protected $fillable = [
        'committee_id',
        'member_type',
        'member_id',
        'role',
        'name_consent_at',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'name_consent_at' => 'datetime',
    ];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function member()
    {
        return $this->morphTo();
    }

    public function isConvenor(): bool
    {
        return $this->role === 'convenor';
    }

    /**
     * Committee lists name people. A parent who otherwise appears under an
     * anonymous code agreed to be named when they joined, so use the real
     * name here — but fall back to the public code if that consent is
     * somehow missing, rather than publishing it anyway.
     */
    public function getDisplayNameAttribute(): string
    {
        $member = $this->member;

        if (! $member) {
            return 'Former member';
        }

        if ($this->name_consent_at === null && ($member->public_name_mode ?? null) === 'anon_code') {
            return $member->public_display_name;
        }

        return $member->full_name;
    }
}
