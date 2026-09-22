<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Committee extends Model
{
    protected $table = 'committees';

    /** The levels a committee can sit at, in order of scope. */
    public const TYPES = [
        'regional' => 'East Cork',
        'school' => 'School',
        'class' => 'Class',
        'activity' => 'Activity',
    ];

    protected $fillable = [
        'area_id',
        'name',
        'slug',
        'committee_type',
        'parent_committee_id',
        'school_id',
        'school_class_id',
        'town',
        'objectives',
        'primary_contact_id',
        'status',
    ];

    /** Committees are linked by slug publicly, never by id. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function parentCommittee()
    {
        return $this->belongsTo(Committee::class, 'parent_committee_id');
    }

    public function childCommittees()
    {
        return $this->hasMany(Committee::class, 'parent_committee_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function members()
    {
        return $this->hasMany(CommitteeMember::class)->orderBy('role')->orderBy('joined_at');
    }

    public function convenorRecord()
    {
        return $this->hasOne(CommitteeMember::class)->where('role', 'convenor');
    }

    /** A committee with no convenor is the one anyone may step forward for. */
    public function hasConvenor(): bool
    {
        return $this->members->contains(fn (CommitteeMember $m) => $m->isConvenor());
    }

    public function isEmpty(): bool
    {
        return $this->members->isEmpty();
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->committee_type] ?? Str::headline($this->committee_type);
    }

    /**
     * Is this person already on the committee? Takes either register.
     */
    public function membershipFor(?Model $person): ?CommitteeMember
    {
        if (! $person) {
            return null;
        }

        return $this->members
            ->firstWhere(fn (CommitteeMember $m) => $m->member_type === $person::class
                && (int) $m->member_id === (int) $person->id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
