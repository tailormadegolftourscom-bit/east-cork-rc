<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Person;
use Illuminate\Http\Request;

class SupporterController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::with(['supporter', 'children'])
            ->whereHas('supporter');

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $people = $query->orderBy('last_name')->orderBy('first_name')->paginate(25)->withQueryString();

        return view('admin.supporters.index', compact('people'));
    }

    public function show(Person $person)
    {
        $person->load([
            'supporter',
            'children.schoolLink.currentSchool',
            'children.schoolLink.currentSchoolClass',
        ]);

        return view('admin.supporters.show', compact('person'));
    }

    public function updateChildAuditStatus(Request $request, Child $child)
    {
        $validated = $request->validate([
            'audit_status' => ['required', 'in:pending,reviewed,verified'],
        ]);

        $child->update($validated);

        return back()->with('success', $child->first_name . '\'s audit status updated.');
    }
}
