<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Parents;
use App\Support\ChildMilestones;

class Child extends Model
{
    protected $table = 'children';

    protected $fillable = [
        'parent_id',
        'first_name',
        'last_name',
        'public_label',
        'audit_status',
        'identity_visibility',
        'class_visibility',
    ];

    public function getMilestoneBadgeAttribute(): string
    {
        return ChildMilestones::badgeFor($this->created_at);
    }

    public function getVisibleIdentityAttribute(): string
    {
        if ($this->identity_visibility === 'code_name_first_name') {
            return $this->public_label.' ('.$this->first_name.')';
        }

        return $this->public_label;
    }

    public function owner()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function schoolLink()
    {
        return $this->hasOne(ChildSchoolLink::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Parents::class, 'child_guardians', 'child_id', 'parent_id')->withTimestamps();
    }
}
