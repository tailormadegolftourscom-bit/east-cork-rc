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
        'principal_person_id',
        'vice_principal_person_id',
        'secretary_person_id',
        'notes',
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

    public function principal()
    {
        return $this->belongsTo(Person::class, 'principal_person_id');
    }

    public function vicePrincipal()
    {
        return $this->belongsTo(Person::class, 'vice_principal_person_id');
    }

    public function secretary()
    {
        return $this->belongsTo(Person::class, 'secretary_person_id');
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
