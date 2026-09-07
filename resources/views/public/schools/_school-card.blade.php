@php
    $collapseId = 'classes-' . $school->id;

    $gradeGroups = $school->classes->groupBy('class_level')->map(function ($classesInGrade, $classLevel) {
        $label = \App\Models\SchoolClass::levelLabel($classLevel);
        $hasNamedStream = $classesInGrade->contains(fn ($c) => $c->display_name !== $label);

        $entries = collect();

        foreach ($classesInGrade as $class) {
            foreach ($class->childLinks ?? [] as $link) {
                $child = $link->child ?? null;

                if (! $child) {
                    continue;
                }

                $entry = $child->visible_identity;

                if ($hasNamedStream && $child->class_visibility === 'specific' && $class->display_name !== $label) {
                    $entry .= ' — ' . $class->display_name;
                }

                $parentName = optional($child->parentPerson)->public_display_name;

                if ($parentName) {
                    $entry .= ' (parent: ' . $parentName . ')';
                }

                $entries->push($entry);
            }
        }

        return [
            'label' => $label,
            'registered_children_count' => $classesInGrade->sum('registered_children_count'),
            'entries' => $entries,
        ];
    })->sortBy(fn ($group, $classLevel) => array_search($classLevel, array_keys(\App\Models\SchoolClass::levelLabels())));
@endphp

<div class="col-lg-6" data-school-search="{{ Str::lower($school->name . ' ' . $school->town) }}">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h3 class="h5 mb-0">{{ $school->name }}</h3>

                <span class="badge {{ $school->support_status === 'supporting' ? 'text-bg-success' : 'text-bg-warning' }}">
                    {{ $school->support_status === 'supporting' ? 'School Supporting' : 'School Undecided' }}
                </span>
            </div>

            <p class="text-muted mb-2">
                {{ $school->town ?: 'East Cork' }}
            </p>

            <p class="mb-2">
                <span class="badge bg-light text-dark border">
                    {{ $school->registered_children_count }}
                    {{ Str::plural('child', $school->registered_children_count) }} registered
                </span>
            </p>

            @if ($school->school_phone)
                <p class="mb-1 small text-muted">Tel: {{ $school->school_phone }}</p>
            @endif

            @if ($school->website_url)
                <p class="mb-2">
                    <a href="{{ $school->website_url }}" target="_blank" class="text-decoration-none">
                        School website
                    </a>
                </p>
            @endif

            @if ($gradeGroups->isNotEmpty())
                <button
                    class="btn btn-sm btn-outline-secondary"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                >
                    See classes
                </button>

                <div class="collapse mt-3" id="{{ $collapseId }}">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Class</th>
                            <th class="text-end">Registered</th>
                            @auth
                                <th>Children</th>
                            @endauth
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($gradeGroups as $grade)
                            @php($entryId = 'grade-entries-' . $school->id . '-' . $loop->index)
                            <tr>
                                <td>{{ $grade['label'] }}</td>
                                <td class="text-end">{{ $grade['registered_children_count'] }}</td>
                                @auth
                                    <td class="small text-muted">
                                        @if ($grade['entries']->isNotEmpty())
                                            <button
                                                type="button"
                                                class="btn btn-link btn-sm p-0 grade-expand-toggle"
                                                data-target="{{ $entryId }}"
                                            >
                                                Expand
                                            </button>
                                            <span id="{{ $entryId }}" hidden>{{ $grade['entries']->join(', ') }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @auth
                        <p class="small text-muted mt-2 mb-0">Signed in as a registered parent — hit "Expand" on a class to see who's taking part, shown as each family has chosen to appear.</p>
                    @else
                        <p class="small text-muted mt-2 mb-0">
                            <a href="{{ route('login') }}">Log in</a> to see who else is taking part in each class.
                        </p>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.grade-expand-toggle');

            if (! btn) {
                return;
            }

            const target = document.getElementById(btn.dataset.target);

            if (! target) {
                return;
            }

            target.hidden = !target.hidden;
            btn.textContent = target.hidden ? 'Expand' : 'Hide';
        });
    </script>
@endonce
