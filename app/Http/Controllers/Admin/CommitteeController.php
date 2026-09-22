<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Area;
use App\Models\Committee;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with(['school', 'schoolClass', 'area', 'members.member'])
            ->orderBy('committee_type')
            ->orderBy('name')
            ->get();

        return view('admin.committees.index', [
            'byType' => $committees->groupBy('committee_type'),
            'types' => Committee::TYPES,
            'withoutConvenor' => $committees->reject->hasConvenor(),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.committees.create', [
            'types' => Committee::TYPES,
            'schools' => School::orderBy('name')->get(),
            // Only classes that do not already have a committee — 188 classes
            // would otherwise be an unusable list.
            'classes' => SchoolClass::with('school')
                ->whereNotIn('id', Committee::whereNotNull('school_class_id')->pluck('school_class_id'))
                ->orderBy('school_id')->orderBy('sort_order')->get(),
            'parents' => Committee::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'committee_type' => ['required', Rule::in(array_keys(Committee::TYPES))],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:committees,slug'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'school_class_id' => ['nullable', 'exists:school_classes,id'],
            'parent_committee_id' => ['nullable', 'exists:committees,id'],
            'town' => ['nullable', 'string', 'max:100'],
            'objectives' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validated['committee_type'] === 'class' && empty($validated['school_class_id'])) {
            return back()->withInput()->withErrors(['school_class_id' => 'Choose the class this committee is for.']);
        }

        if ($validated['committee_type'] === 'school' && empty($validated['school_id'])) {
            return back()->withInput()->withErrors(['school_id' => 'Choose the school this committee is for.']);
        }

        // A class committee belongs to its school's committee; a school
        // committee to the regional one. Keeps the tree sensible without
        // making the admin pick every time.
        if (empty($validated['parent_committee_id'])) {
            $validated['parent_committee_id'] = $this->inferParent($validated);
        }

        $committee = Committee::create($validated + [
            'area_id' => Area::defaultId(),
            'status' => 'active',
        ]);

        AdminAuditLog::record('committee.create', $committee, 'Created from the admin screen.', [
            'name' => $committee->name,
            'type' => $committee->committee_type,
        ]);

        return redirect()
            ->route('admin.committees.index')
            ->with('success', $committee->name.' created. It will show publicly with a "Become Convenor" button until someone steps forward.');
    }

    public function destroy(Request $request, Committee $committee)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($committee->childCommittees()->exists()) {
            return back()->with('error', 'This committee has others nested under it. Move or delete those first.');
        }

        $name = $committee->name;

        AdminAuditLog::record('committee.delete', $committee, $validated['reason'], [
            'name' => $name,
            'members' => $committee->members()->count(),
        ]);

        // Membership rows cascade with it.
        $committee->delete();

        return redirect()
            ->route('admin.committees.index')
            ->with('success', $name.' deleted.');
    }

    private function inferParent(array $data): ?int
    {
        if ($data['committee_type'] === 'class' && ! empty($data['school_class_id'])) {
            $class = SchoolClass::find($data['school_class_id']);

            return Committee::where('school_id', $class?->school_id)
                ->where('committee_type', 'school')
                ->value('id');
        }

        if (in_array($data['committee_type'], ['school', 'activity'], true)) {
            return Committee::where('committee_type', 'regional')->value('id');
        }

        return null;
    }

    /** Suggests a slug for the create form without forcing it. */
    public static function suggestSlug(string $name): string
    {
        return Str::slug($name);
    }
}
