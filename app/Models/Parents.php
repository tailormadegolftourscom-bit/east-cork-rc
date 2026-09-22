<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A single human in the system — identity and login in one row.
 *
 * Named `Parents` rather than `Parent` because `parent` is a reserved word in
 * PHP and cannot be used as a class name. Displayed as "Parent" everywhere in
 * the UI; the plural only ever appears in code.
 *
 * The table also carries admin and school logins, because Laravel
 * authenticates against exactly one table. `user_type` tells them apart.
 */
class Parents extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'parents';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'public_name_mode',
        'preferred_contact_method',
        'phone_verified_at',
        'name',
        'password',
        'is_admin',
        'user_type',
        'school_id',
        'email_verified_at',
        'registration_completed_at',
        'onboarded_at',
        'invited_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'suspended_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'registration_completed_at' => 'datetime',
            'onboarded_at' => 'datetime',
            'no_children_reminder_sent_at' => 'datetime',
            'pending_reminder_sent_at' => 'datetime',
            'invited_at' => 'datetime',
            'is_admin' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Has this account actually been taken up? A co-parent invite arrives
     * pre-verified, so email_verified_at cannot answer this — only a password
     * the person chose themselves can. Drives the 3/6/9 reminder sweep.
     */
    public function hasCompletedRegistration(): bool
    {
        return $this->registration_completed_at !== null;
    }

    /**
     * Has this parent been through /parent/start and chosen how they want to
     * be contacted and shown publicly? Separate from having a password —
     * someone can set one and stop before the form.
     */
    public function hasOnboarded(): bool
    {
        return $this->onboarded_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->user_type === 'admin';
    }

    public function getFullNameAttribute(): string
    {
        $full = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));

        return $full !== '' ? $full : (string) $this->name;
    }

    public function getPublicDisplayNameAttribute(): string
    {
        if ($this->public_name_mode === 'anon_code') {
            return 'AD'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
        }

        return $this->full_name;
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /** Children this parent registered and owns. */
    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

    /** Children this parent was added to as a co-parent. */
    public function guardianOfChildren()
    {
        return $this->belongsToMany(Child::class, 'child_guardians', 'parent_id', 'child_id')
            ->withTimestamps();
    }

    /**
     * Kinds of support this parent also offers — teacher, volunteer and so
     * on. Every parent supports the initiative by definition; these are the
     * extras on top, drawn from the same list the supporters register uses.
     */
    /** Committees this person sits on, in either register. */
    public function committeeMemberships()
    {
        return $this->morphMany(CommitteeMember::class, 'member');
    }

    public function categories()
    {
        return $this->morphToMany(
            SupporterCategory::class,
            'linkable',
            'supporter_category_links',
            'linkable_id',
            'supporter_category_id'
        )->withTimestamps();
    }
}
