<?php

namespace App\Http\Controllers;

use App\Models\Committee;

/**
 * Public committee pages. Committees are meant to be found and joined, so
 * these are open to everyone — the act of being on one is public, which is
 * why joining asks for consent to be named.
 */
class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::active()
            ->with(['members.member', 'school', 'schoolClass', 'childCommittees'])
            ->orderBy('name')
            ->get();

        // Grouped by level rather than nested, so an empty school committee is
        // as easy to find as a busy one — finding the empty ones is the point.
        return view('committees.index', [
            'byType' => $committees->groupBy('committee_type'),
            'types' => Committee::TYPES,
            'needingConvenor' => $committees->reject->hasConvenor()->count(),
        ]);
    }

    public function show(Committee $committee)
    {
        abort_unless($committee->status === 'active', 404);

        $committee->load([
            'members.member',
            'school',
            'schoolClass',
            'parentCommittee',
            'childCommittees.members',
        ]);

        $viewer = auth()->user();

        return view('committees.show', [
            'committee' => $committee,
            'convenor' => $committee->members->firstWhere('role', 'convenor'),
            'ordinaryMembers' => $committee->members->where('role', 'member'),
            'myMembership' => $committee->membershipFor($viewer),
            // Only parents can act for themselves; supporters are added by the
            // convenor, because the supporters register has no logins.
            'canAct' => $viewer && $viewer->user_type === 'parent',
        ]);
    }
}
