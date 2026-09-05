<?php

namespace App\Http\Controllers;

use App\Models\School;

class PublicSchoolController extends Controller
{
    public function index()
    {
        $schools = School::where('status', 'active')
            ->orderByRaw("CASE WHEN school_type = 'primary' THEN 1 ELSE 2 END")
            ->orderBy('town')
            ->orderBy('name')
            ->get()
            ->groupBy('school_type');

        return view('public.schools.index', compact('schools'));
    }
}
