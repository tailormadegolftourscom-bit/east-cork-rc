<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with(['school', 'area'])
            ->orderBy('committee_type')
            ->orderBy('name')
            ->get();

        $regional = $committees->whereNull('parent_committee_id');
        $bySchool = $committees->whereNotNull('parent_committee_id')->groupBy('parent_committee_id');

        return view('admin.committees.index', compact('regional', 'bySchool'));
    }
}
