<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildSchoolLink extends Model
{
    protected $table = 'child_school_links';

    protected $fillable = [
        'child_id',
        'current_school_id',
        'current_school_class_id',
        'likely_secondary_school_id',
        'transition_status',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function currentSchool()
    {
        return $this->belongsTo(School::class, 'current_school_id');
    }

    public function currentSchoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'current_school_class_id');
    }

    public function likelySecondarySchool()
    {
        return $this->belongsTo(School::class, 'likely_secondary_school_id');
    }
}
