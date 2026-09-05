<?php

namespace App\Http\Controllers;

use App\Models\School;

class PublicSchoolController extends Controller
{
    public function index()
    {
        $schools = School::where('status', 'active')
            ->withCount('childLinks as registered_children_count')
            ->with(['classes' => function ($query) {
                $query->where('is_active', true)->withCount('childLinks as registered_children_count');
            }])
            ->orderByRaw("CASE WHEN school_type = 'primary' THEN 1 ELSE 2 END")
            ->orderBy('town')
            ->orderBy('name')
            ->get()
            ->groupBy('school_type');

        return view('public.schools.index', compact('schools'));
    }
}
