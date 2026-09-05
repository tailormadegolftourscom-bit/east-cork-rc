<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\School;
use App\Models\Supporter;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'schools_listed' => School::where('status', 'active')->count(),
            'schools_supporting' => School::where('status', 'active')->where('support_status', 'supporting')->count(),
            'parents_registered' => Supporter::where('is_active', true)->count(),
            'children_registered' => Child::count(),
        ];

        return view('welcome', compact('stats'));
    }
}
