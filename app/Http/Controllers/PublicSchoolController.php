<?php

namespace App\Http\Controllers;

use App\Models\School;

class PublicSchoolController extends Controller
{
    public function index()
    {
        $schools = $this->schoolsWithCounts();

        return view('public.schools.index', compact('schools'));
    }

    public function forParents()
    {
        $schools = $this->schoolsWithCounts();

        return view('public.parents', compact('schools'));
    }

    private function schoolsWithCounts()
    {
        $showChildNames = auth()->check();

        return School::where('status', 'active')
            ->withCount('childLinks as registered_children_count')
            ->with(['classes' => function ($query) use ($showChildNames) {
                $query->where('is_active', true)->withCount('childLinks as registered_children_count');

                if ($showChildNames) {
                    $query->with('childLinks.child:id,public_label');
                }
            }])
            ->orderByRaw("CASE WHEN school_type = 'primary' THEN 1 ELSE 2 END")
            ->orderBy('town')
            ->orderBy('name')
            ->get()
            ->groupBy('school_type');
    }
}
