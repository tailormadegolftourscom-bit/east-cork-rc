<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildSchoolLink;
use App\Models\School;

class PublicSchoolController extends Controller
{
    public function index()
    {
        $schools = $this->schoolsWithCounts();
        $ownSchoolIds = $this->ownSchoolIds();

        return view('public.schools.index', compact('schools', 'ownSchoolIds'));
    }

    public function forParents()
    {
        $schools = $this->schoolsWithCounts();
        $ownSchoolIds = $this->ownSchoolIds();

        return view('public.parents', compact('schools', 'ownSchoolIds'));
    }

    /**
     * Schools where the logged-in parent has a child of their own (owned
     * or guarded) — they see the real, specific classes there instead of
     * the general grade-level rollup used for every other school.
     */
    private function ownSchoolIds()
    {
        $person = auth()->user()?->person;

        if (! $person) {
            return collect();
        }

        $childIds = Child::where('parent_person_id', $person->id)
            ->orWhereHas('guardians', fn ($q) => $q->where('people.id', $person->id))
            ->pluck('id');

        return ChildSchoolLink::whereIn('child_id', $childIds)
            ->pluck('current_school_id')
            ->unique();
    }

    private function schoolsWithCounts()
    {
        $showChildNames = auth()->check();

        return School::where('status', 'active')
            ->withCount('childLinks as registered_children_count')
            ->with(['classes' => function ($query) use ($showChildNames) {
                $query->where('is_active', true)->withCount('childLinks as registered_children_count');

                if ($showChildNames) {
                    $query->with([
                        'childLinks.child:id,first_name,public_label,identity_visibility,class_visibility,parent_person_id',
                        'childLinks.child.parentPerson:id,first_name,last_name,public_name_mode',
                    ]);
                }
            }])
            ->orderByRaw("CASE WHEN school_type = 'primary' THEN 1 ELSE 2 END")
            ->orderBy('town')
            ->orderBy('name')
            ->get()
            ->groupBy('school_type');
    }
}
