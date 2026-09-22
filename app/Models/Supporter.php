<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Someone who backs the initiative but is not a parent here.
 *
 * Parents are supporters by definition and live in `parents`; this table is
 * for the rest — teachers, celebrities, neighbours, and the news-only list
 * that used to be `subscribers`. No login: a celebrity offering a message for
 * the kids has no reason to need a dashboard.
 */
class Supporter extends Model
{
    protected $table = 'supporters';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'preferred_contact_method',
        'is_active',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

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
