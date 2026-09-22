<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\School;
use App\Models\Parents;
use App\Models\Supporter;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'schools_listed' => School::where('status', 'active')->count(),
            'schools_primary' => School::where('status', 'active')->where('school_type', 'primary')->count(),
            'schools_secondary' => School::where('status', 'active')->where('school_type', 'secondary')->count(),
            'schools_supporting' => School::where('status', 'active')->where('support_status', 'supporting')->count(),
            // One figure, as agreed: every parent is a supporter, plus the
            // non-parent supporters register.
            'parents_registered' => Parents::where('user_type', 'parent')->whereNotNull('onboarded_at')->count(),
            'supporters_total' => Parents::where('user_type', 'parent')->whereNotNull('onboarded_at')->count()
                + Supporter::where('is_active', true)->count(),
            'children_registered' => Child::count(),
        ];

        return view('welcome', compact('stats'));
    }
}
