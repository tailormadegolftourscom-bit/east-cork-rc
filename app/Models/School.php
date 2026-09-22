<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';

    protected $fillable = [
        'area_id',
        'name',
        'alternative_name',
        'slug',
        'roll_number',
        'school_type',
        'address_line_1',
        'address_line_2',
        'town',
        'eircode',
        'website_url',
        'principal_email',
        'vice_principal_email',
        'secretary_email',
        'principal_name',
        'vice_principal_name',
        'secretary_name',
        'school_phone',
        'support_status',
        'status',
        'classes_confirmed',
        'invite_sent_at',
        'invite_response_at',
        'invite_response_note',
        'principal_id',
        'vice_principal_id',
        'secretary_id',
        'notes',
    ];

    protected $casts = [
        'classes_confirmed' => 'boolean',
        'invite_sent_at' => 'datetime',
        'invite_response_at' => 'datetime',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class)->orderBy('sort_order')->orderBy('display_name');
    }

    public function childLinks()
    {
        return $this->hasMany(ChildSchoolLink::class, 'current_school_id');
    }

    /** The login created for this school, if an invite has been taken up. */
    public function account()
    {
        return $this->hasOne(Parents::class, 'school_id')->where('user_type', 'school');
    }

    public function inviteSent(): bool
    {
        return $this->invite_sent_at !== null;
    }

    /**
     * Has the school actually come back to us? Either an admin marked a reply,
     * or the account was taken up by setting a password — the latter needs no
     * button, it is simply true.
     */
    public function inviteAnswered(): bool
    {
        return $this->invite_response_at !== null
            || (bool) $this->account?->registration_completed_at;
    }

    public function daysSinceInvite(): ?int
    {
        return $this->invite_sent_at
            ? (int) $this->invite_sent_at->startOfDay()->diffInDays(now()->startOfDay())
            : null;
    }

    public function principal()
    {
        return $this->belongsTo(Parents::class, 'principal_id');
    }

    public function vicePrincipal()
    {
        return $this->belongsTo(Parents::class, 'vice_principal_id');
    }

    public function secretary()
    {
        return $this->belongsTo(Parents::class, 'secretary_id');
    }

    public function committee()
    {
        return $this->hasOne(Committee::class, 'school_id')
            ->where('committee_type', 'school');
    }

    /**
     * Active class rows still missing a pupil total. A school is only
     * meaningfully ready to go active once every active class it has
     * reports a total — this is guidance for admin, not an automatic gate.
     */
    public function classesMissingPupilTotals()
    {
        return $this->classes()
            ->where('is_active', true)
            ->whereNull('total_pupils')
            ->get();
    }

    public function isReadyForActivation(): bool
    {
        $activeClassCount = $this->classes()->where('is_active', true)->count();

        return $activeClassCount > 0 && $this->classesMissingPupilTotals()->isEmpty();
    }
}
