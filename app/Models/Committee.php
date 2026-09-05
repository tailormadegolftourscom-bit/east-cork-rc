<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $table = 'committees';

    protected $fillable = [
        'area_id',
        'name',
        'slug',
        'committee_type',
        'parent_committee_id',
        'school_id',
        'town',
        'primary_contact_person_id',
        'status',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function parentCommittee()
    {
        return $this->belongsTo(Committee::class, 'parent_committee_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
