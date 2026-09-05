<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    private array $classLevelLabels = [
        'junior_infants' => 'Junior Infants',
        'senior_infants' => 'Senior Infants',
        '1st_class' => '1st Class',
        '2nd_class' => '2nd Class',
        '3rd_class' => '3rd Class',
        '4th_class' => '4th Class',
        '5th_class' => '5th Class',
        '6th_class' => '6th Class',
        '1st_year' => '1st Year',
        '2nd_year' => '2nd Year',
        '3rd_year' => '3rd Year',
        '4th_year' => '4th Year',
        '5th_year' => '5th Year',
        '6th_year' => '6th Year',
    ];

    public function index(School $school)
    {
        $classes = $school->classes()->get();

        return view('admin.school-classes.index', compact('school', 'classes'));
    }

    public function create(School $school)
    {
        $classLevelLabels = $this->allowedCreateClassLevels($school);

        return view('admin.school-classes.create', compact('school', 'classLevelLabels'));
    }

    public function store(Request $request, School $school)
    {
        $allowedLevels = array_keys($this->allowedCreateClassLevels($school));

        $validated = $request->validate([
            'class_level' => ['required', Rule::in($allowedLevels)],
            'class_stream' => ['nullable', 'string', 'max:100'],
            'total_pupils' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['class_stream'] = ($validated['class_stream'] ?? null) ? trim($validated['class_stream']) : null;

        $duplicateQuery = SchoolClass::where('school_id', $school->id)
            ->where('class_level', $validated['class_level']);

        if ($validated['class_stream']) {
            $duplicateQuery->where('class_stream', $validated['class_stream']);
        } else {
            $duplicateQuery->whereNull('class_stream');
        }

        if ($duplicateQuery->exists()) {
            return back()
                ->withErrors([
                    'class_stream' => 'That class already exists for this school.',
                ])
                ->withInput();
        }

        SchoolClass::create([
            'school_id' => $school->id,
            'class_level' => $validated['class_level'],
            'class_stream' => $validated['class_stream'],
            'display_name' => $this->makeDisplayName($validated['class_level'], $validated['class_stream']),
            'sort_order' => $this->makeSortOrder($validated['class_level']),
            'total_pupils' => $validated['total_pupils'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.schools.classes.index', $school)
            ->with('success', 'Class added.');
    }

    public function edit(School $school, SchoolClass $schoolClass)
    {
        $this->ensureBelongsToSchool($school, $schoolClass);

        $classLevelLabels = $this->allowedEditClassLevels($school);

        return view('admin.school-classes.edit', compact('school', 'schoolClass', 'classLevelLabels'));
    }

    public function update(Request $request, School $school, SchoolClass $schoolClass)
    {
        $this->ensureBelongsToSchool($school, $schoolClass);

        $allowedLevels = array_keys($this->allowedEditClassLevels($school));

        $validated = $request->validate([
            'class_level' => ['required', Rule::in($allowedLevels)],
            'class_stream' => ['nullable', 'string', 'max:100'],
            'total_pupils' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['class_stream'] = ($validated['class_stream'] ?? null) ? trim($validated['class_stream']) : null;

        $duplicateQuery = SchoolClass::where('school_id', $school->id)
            ->where('class_level', $validated['class_level'])
            ->where('id', '!=', $schoolClass->id);

        if ($validated['class_stream']) {
            $duplicateQuery->where('class_stream', $validated['class_stream']);
        } else {
            $duplicateQuery->whereNull('class_stream');
        }

        if ($duplicateQuery->exists()) {
            return back()
                ->withErrors([
                    'class_stream' => 'That class already exists for this school.',
                ])
                ->withInput();
        }

        $schoolClass->update([
            'class_level' => $validated['class_level'],
            'class_stream' => $validated['class_stream'],
            'display_name' => $this->makeDisplayName($validated['class_level'], $validated['class_stream']),
            'sort_order' => $this->makeSortOrder($validated['class_level']),
            'total_pupils' => $validated['total_pupils'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.schools.classes.index', $school)
            ->with('success', 'Class updated.');
    }

    public function destroy(School $school, SchoolClass $schoolClass)
    {
        $this->ensureBelongsToSchool($school, $schoolClass);
        Gate::authorize('delete', $schoolClass);

        $schoolClass->delete();

        return redirect()
            ->route('admin.schools.classes.index', $school)
            ->with('success', 'Class deleted.');
    }

    private function ensureBelongsToSchool(School $school, SchoolClass $schoolClass): void
    {
        abort_unless($schoolClass->school_id === $school->id, 404);
    }

    private function allowedCreateClassLevels(School $school): array
    {
        if ($school->school_type === 'primary') {
            return [
                '4th_class' => '4th Class',
                '5th_class' => '5th Class',
                '6th_class' => '6th Class',
            ];
        }

        return [
            '1st_year' => '1st Year',
            '2nd_year' => '2nd Year',
            '3rd_year' => '3rd Year',
            '4th_year' => '4th Year',
            '5th_year' => '5th Year',
            '6th_year' => '6th Year',
        ];
    }

    private function allowedEditClassLevels(School $school): array
    {
        if ($school->school_type === 'primary') {
            return [
                'junior_infants' => 'Junior Infants',
                'senior_infants' => 'Senior Infants',
                '1st_class' => '1st Class',
                '2nd_class' => '2nd Class',
                '3rd_class' => '3rd Class',
                '4th_class' => '4th Class',
                '5th_class' => '5th Class',
                '6th_class' => '6th Class',
            ];
        }

        return [
            '1st_year' => '1st Year',
            '2nd_year' => '2nd Year',
            '3rd_year' => '3rd Year',
            '4th_year' => '4th Year',
            '5th_year' => '5th Year',
            '6th_year' => '6th Year',
        ];
    }

    private function makeDisplayName(string $classLevel, ?string $classStream): string
    {
        if ($classStream) {
            return $classStream;
        }

        return $this->classLevelLabels[$classLevel];
    }

    private function makeSortOrder(string $classLevel): int
    {
        return match ($classLevel) {
            'junior_infants' => 10,
            'senior_infants' => 20,
            '1st_class' => 30,
            '2nd_class' => 40,
            '3rd_class' => 50,
            '4th_class' => 60,
            '5th_class' => 70,
            '6th_class' => 80,
            '1st_year' => 90,
            '2nd_year' => 100,
            '3rd_year' => 110,
            '4th_year' => 120,
            '5th_year' => 130,
            '6th_year' => 140,
        };
    }
}
