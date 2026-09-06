<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Support\ChildMilestones;

class Child extends Model
{
    protected $table = 'children';

    protected $fillable = [
        'parent_person_id',
        'first_name',
        'last_name',
        'public_label',
        'audit_status',
    ];

    public function getMilestoneBadgeAttribute(): string
    {
        return ChildMilestones::badgeFor($this->created_at);
    }

    public function parentPerson()
    {
        return $this->belongsTo(Person::class, 'parent_person_id');
    }

    public function schoolLink()
    {
        return $this->hasOne(ChildSchoolLink::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Person::class, 'child_guardians')->withTimestamps();
    }
}
