<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildSchoolLink;
use App\Models\Parents;
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

        $schools = School::where('status', 'active')
            ->with('classes')
            ->orderBy('school_type')
            ->orderBy('town')
            ->orderBy('name')
            ->get();

        $codeNameSuggestions = ChildCodeNames::suggestions();
        $codeNameWordLists = ChildCodeNames::wordLists();

        // A co-parent already has the other parent's children on their
        // dashboard. Say so before they add a second copy of the same child.
        $alreadyCoParented = auth()->user()->guardianOfChildren()->with('owner')->get();

        return view('parent.children.create', compact(
            'schools', 'codeNameSuggestions', 'codeNameWordLists', 'alreadyCoParented'
        ));
    }

    /**
     * A child already on this parent's dashboard, through co-parenting, whose
     * name matches the one being added. Matches on first name alone when no
     * surname is given, because that is all many of these records carry.
     */
    private function possibleDuplicate(Parents $parent, string $firstName, ?string $lastName): ?Child
    {
        $first = mb_strtolower(trim($firstName));
        $last = mb_strtolower(trim((string) $lastName));

        return $parent->guardianOfChildren()->with('owner')->get()
            ->first(function (Child $child) use ($first, $last) {
                if (mb_strtolower(trim($child->first_name)) !== $first) {
                    return false;
                }

                $existingLast = mb_strtolower(trim((string) $child->last_name));

                // Either surname missing means the first name is all we have
                // to go on, and a match is worth raising.
                return $existingLast === '' || $last === '' || $existingLast === $last;
            });
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Child::class);

        $parent = auth()->user();
        $validated = $this->validateChild($request);

        // Flag a child who looks like one they already co-parent, rather than
        // letting the same person be registered twice under two parents. They
        // can say it really is a different child and carry on.
        if (! $request->boolean('confirm_not_duplicate')) {
            $clash = $this->possibleDuplicate($parent, $validated['first_name'], $validated['last_name'] ?? null);

            if ($clash) {
                return back()
                    ->withInput()
                    ->with('duplicate_child', [
                        'name' => trim($clash->first_name.' '.$clash->last_name),
                        'owner' => optional($clash->owner)->full_name,
                    ]);
            }
        }

        $codeName = trim($validated['public_label'] ?? '');

        if ($codeName === '') {
            $codeName = ChildCodeNames::unique();
        }

        $child = DB::transaction(function () use ($parent, $validated, $codeName) {
            $child = Child::create([
                'parent_id' => $parent->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? null,
                'public_label' => $codeName,
                'identity_visibility' => $validated['identity_visibility'] ?? 'code_name',
                'class_visibility' => $validated['class_visibility'] ?? 'general',
            ]);

            $this->syncSchoolLink($child, $validated);
            $this->copyExistingGuardians($child, $parent);

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
        $currentSchoolId = $child->schoolLink?->current_school_id;

        $schools = School::where('status', 'active')
            ->when($currentSchoolId, fn ($q) => $q->orWhere('id', $currentSchoolId))
            ->with('classes')
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
                'identity_visibility' => $validated['identity_visibility'] ?? 'code_name',
                'class_visibility' => $validated['class_visibility'] ?? 'general',
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

    public function destroy(Request $request, Child $child)
    {
        Gate::authorize('delete', $child);

        $validated = $request->validate([
            'confirm_name' => ['required', 'string'],
        ]);

        if (trim($validated['confirm_name']) !== $child->first_name) {
            return back()->with('error', 'Name did not match — ' . $child->first_name . ' was not removed.');
        }

        $name = $child->first_name;
        $child->delete();

        return redirect()
            ->route('parent.dashboard')
            ->with('success', $name . ' has been removed.');
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
            'identity_visibility' => ['nullable', 'in:code_name,code_name_first_name'],
            'class_visibility' => ['nullable', 'in:general,specific'],
            'school_id' => ['nullable', Rule::exists('schools', 'id')],
            'school_class_id' => [
                'nullable',
                'required_with:school_id',
                Rule::exists('school_classes', 'id')->where('school_id', $request->input('school_id')),
            ],
            'transition_choice' => ['nullable', 'in:undecided,share,not_stated'],
            'likely_secondary_school_id' => ['nullable', Rule::exists('schools', 'id')],
            'transition_status' => ['nullable', 'in:considering,likely,confirmed'],
            'intended_secondary' => ['nullable', 'string'],
            'intended_status' => ['nullable', 'in:considering,likely,confirmed'],
            'unlisted_secondary_name' => ['nullable', 'string', 'max:150'],
        ]);

        if ($validated['school_id'] ?? null) {
            $request->validate([
                'school_class_id' => ['required'],
            ]);
        }

        // A 6th class child must say where they are heading next: one of the
        // listed secondary schools, or Undecided. That is what lets Rebels
        // bound for the same school be put together before 1st Year.
        $classLevel = SchoolClass::find($validated['school_class_id'] ?? null)?->class_level;

        if ($classLevel === '6th_class') {
            $secondaryIds = School::where('school_type', 'secondary')->pluck('id')->map(fn ($id) => (string) $id);

            $request->validate([
                'intended_secondary' => ['required', Rule::in($secondaryIds->push('undecided', 'unlisted')->all())],
                'unlisted_secondary_name' => ['required_if:intended_secondary,unlisted'],
            ], [
                'intended_secondary.required' => 'Please choose the secondary school your child intends to go to, or Undecided.',
                'intended_secondary.in' => 'Please choose a secondary school from the list, Unlisted, or Undecided.',
                'unlisted_secondary_name.required_if' => 'Please type the name of the unlisted secondary school.',
            ]);
        }

        return $validated;
    }

    private function copyExistingGuardians(Child $child, Parents $parent): void
    {
        // Co-parents on the creator's other children.
        $guardianIds = Parents::whereHas('guardianOfChildren', function ($q) use ($parent) {
                $q->where('children.parent_id', $parent->id);
            })
            ->pluck('parents.id');

        // Owners of children the creator is themselves a guardian on.
        $ownerIds = Child::whereHas('guardians', function ($q) use ($parent) {
                $q->where('parents.id', $parent->id);
            })
            ->pluck('parent_id');

        $sharedParentIds = $guardianIds->merge($ownerIds)
            ->unique()
            ->reject(fn ($id) => $id === $parent->id);

        if ($sharedParentIds->isNotEmpty()) {
            $child->guardians()->syncWithoutDetaching($sharedParentIds);
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
        $intended = $validated['intended_secondary'] ?? null;
        $unlistedName = null;

        if ($classLevel === '6th_class') {
            // Required and validated above. Undecided keeps the existing
            // convention: considering, with no school named. Unlisted keeps
            // the typed name until that school is added and linked.
            $likelySecondarySchoolId = ctype_digit((string) $intended) ? (int) $intended : null;
            $unlistedName = $intended === 'unlisted'
                ? trim((string) ($validated['unlisted_secondary_name'] ?? '')) ?: null
                : null;
            $transitionStatus = ($likelySecondarySchoolId || $unlistedName)
                ? ($validated['intended_status'] ?? 'likely')
                : 'considering';
        } elseif (! $eligibleForTransition) {
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
                'unlisted_secondary_name' => $unlistedName,
                'transition_status' => $transitionStatus,
            ]
        );
    }
}
