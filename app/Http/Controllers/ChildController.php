<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildSchoolLink;
use App\Models\Person;
use App\Models\School;
use App\Models\SchoolClass;
use App\Support\ChildCodeNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ChildController extends Controller
{
    public function create()
    {
        Gate::authorize('create', Child::class);

        $schools = School::with('classes')
            ->orderBy('school_type')
            ->orderBy('town')
            ->orderBy('name')
            ->get();

        $codeNameSuggestions = ChildCodeNames::suggestions();
        $codeNameWordLists = ChildCodeNames::wordLists();

        return view('parent.children.create', compact('schools', 'codeNameSuggestions', 'codeNameWordLists'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Child::class);

        $person = auth()->user()->person;
        $validated = $this->validateChild($request);

        $codeName = trim($validated['public_label'] ?? '');

        if ($codeName === '') {
            $codeName = ChildCodeNames::unique();
        }

        $child = DB::transaction(function () use ($person, $validated, $codeName) {
            $child = Child::create([
                'parent_person_id' => $person->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? null,
                'public_label' => $codeName,
            ]);

            $this->syncSchoolLink($child, $validated);
            $this->copyExistingGuardians($child, $person);

            return $child;
        });

        return redirect()
            ->route('parent.dashboard')
            ->with('success', $child->first_name . ' has been registered.');
    }

    public function edit(Child $child)
    {
        Gate::authorize('update', $child);

        $child->load('schoolLink');

        $schools = School::with('classes')
            ->orderBy('school_type')
            ->orderBy('town')
            ->orderBy('name')
            ->get();

        $codeNameSuggestions = ChildCodeNames::suggestions();
        $codeNameWordLists = ChildCodeNames::wordLists();

        return view('parent.children.edit', compact('child', 'schools', 'codeNameSuggestions', 'codeNameWordLists'));
    }

    public function update(Request $request, Child $child)
    {
        Gate::authorize('update', $child);

        $validated = $this->validateChild($request);
        $codeName = trim($validated['public_label'] ?? '');

        DB::transaction(function () use ($child, $validated, $codeName) {
            $attributes = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? null,
            ];

            if ($codeName !== '') {
                $attributes['public_label'] = $codeName;
            }

            $child->update($attributes);

            $this->syncSchoolLink($child, $validated);
        });

        return redirect()
            ->route('parent.dashboard')
            ->with('success', $child->first_name . '\'s details have been updated.');
    }

    private function validateChild(Request $request): array
    {
        $validated = $request->validate([
            'first_name' => [
                'required', 'string', 'max:100',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^(child|kid|baby)\s*\d*$/i', trim($value))) {
                        $fail('Please enter your child\'s real first name rather than a placeholder.');
                    }
                },
            ],
            'last_name' => ['nullable', 'string', 'max:100'],
            'public_label' => ['nullable', 'string', 'max:50'],
            'school_id' => ['nullable', Rule::exists('schools', 'id')],
            'school_class_id' => [
                'nullable',
                'required_with:school_id',
                Rule::exists('school_classes', 'id')->where('school_id', $request->input('school_id')),
            ],
            'transition_choice' => ['nullable', 'in:undecided,share,not_stated'],
            'likely_secondary_school_id' => ['nullable', Rule::exists('schools', 'id')],
            'transition_status' => ['nullable', 'in:considering,likely,confirmed'],
        ]);

        if ($validated['school_id'] ?? null) {
            $request->validate([
                'school_class_id' => ['required'],
            ]);
        }

        return $validated;
    }

    private function copyExistingGuardians(Child $child, Person $person): void
    {
        // Co-parents on the creator's other children.
        $guardianIds = Person::whereHas('guardianOfChildren', function ($q) use ($person) {
                $q->where('children.parent_person_id', $person->id);
            })
            ->pluck('people.id');

        // Owners of children the creator is themselves a guardian on.
        $ownerIds = Child::whereHas('guardians', function ($q) use ($person) {
                $q->where('people.id', $person->id);
            })
            ->pluck('parent_person_id');

        $sharedPersonIds = $guardianIds->merge($ownerIds)
            ->unique()
            ->reject(fn ($id) => $id === $person->id);

        if ($sharedPersonIds->isNotEmpty()) {
            $child->guardians()->syncWithoutDetaching($sharedPersonIds);
        }
    }

    private function syncSchoolLink(Child $child, array $validated): void
    {
        if (empty($validated['school_id']) || empty($validated['school_class_id'])) {
            $child->schoolLink()->delete();

            return;
        }

        $classLevel = SchoolClass::find($validated['school_class_id'])?->class_level;
        $eligibleForTransition = in_array($classLevel, ['5th_class', '6th_class'], true);
        $choice = $validated['transition_choice'] ?? 'undecided';

        if (! $eligibleForTransition) {
            $likelySecondarySchoolId = null;
            $transitionStatus = 'not_applicable';
        } elseif ($choice === 'not_stated') {
            $likelySecondarySchoolId = null;
            $transitionStatus = 'not_stated';
        } elseif ($choice === 'share' && ! empty($validated['likely_secondary_school_id'])) {
            $likelySecondarySchoolId = $validated['likely_secondary_school_id'];
            $transitionStatus = $validated['transition_status'] ?? 'considering';
        } else {
            $likelySecondarySchoolId = null;
            $transitionStatus = 'considering';
        }

        ChildSchoolLink::updateOrCreate(
            ['child_id' => $child->id],
            [
                'current_school_id' => $validated['school_id'],
                'current_school_class_id' => $validated['school_class_id'],
                'likely_secondary_school_id' => $likelySecondarySchoolId,
                'transition_status' => $transitionStatus,
            ]
        );
    }
}
