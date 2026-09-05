@php
    $link = $child?->schoolLink;
    $selectedSchoolId = old('school_id', $link?->current_school_id);
    $selectedClassId = old('school_class_id', $link?->current_school_class_id);
    $selectedSecondaryId = old('likely_secondary_school_id', $link?->likely_secondary_school_id);
    $selectedTransitionStatus = old('transition_status', $link?->transition_status);

    $defaultChoice = match (true) {
        $link?->transition_status === 'not_stated' => 'not_stated',
        (bool) $link?->likely_secondary_school_id => 'share',
        default => 'undecided',
    };
    $selectedTransitionChoice = old('transition_choice', $defaultChoice);

    $schoolsData = $schools->mapWithKeys(fn ($school) => [
        $school->id => $school->classes->map(fn ($class) => [
            'id' => $class->id,
            'display_name' => $class->display_name,
            'class_level' => $class->class_level,
        ]),
    ]);
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input
            type="text"
            class="form-control @error('first_name') is-invalid @enderror"
            id="first_name"
            name="first_name"
            value="{{ old('first_name', $child?->first_name) }}"
            required
        >
        @error('first_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="last_name" class="form-label">Last Name <span class="text-muted">(optional)</span></label>
        <input
            type="text"
            class="form-control @error('last_name') is-invalid @enderror"
            id="last_name"
            name="last_name"
            value="{{ old('last_name', $child?->last_name) }}"
        >
        @error('last_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr class="my-4">

<h2 class="h6 mb-1">Code Name</h2>
<p class="text-muted small mb-3">
    This is the fun part &mdash; let your child pick their own code name! It's how they'll appear anywhere
    public, instead of their real name. Pick one of the suggestions below, hit shuffle for more, or type
    your own.
</p>

<div class="mb-3">
    <div class="d-flex flex-wrap gap-2 mb-2" id="code-name-suggestions">
        @foreach ($codeNameSuggestions as $i => $suggestion)
            <button type="button" class="btn btn-outline-primary btn-sm code-name-chip" data-index="{{ $i }}">
                {{ $suggestion }}
            </button>
        @endforeach
        <button type="button" class="btn btn-outline-secondary btn-sm" id="code-name-shuffle">
            &#128256; Shuffle
        </button>
    </div>

    <label for="public_label" class="form-label">Or type your own</label>
    <input
        type="text"
        class="form-control @error('public_label') is-invalid @enderror"
        id="public_label"
        name="public_label"
        maxlength="50"
        placeholder="{{ $codeNameSuggestions[0] ?? 'Shiny Blue Crocodile' }}"
        value="{{ old('public_label', $child && $child->public_label !== 'anonymous' ? $child->public_label : '') }}"
    >
    @error('public_label')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<hr class="my-4">

<h2 class="h6 mb-3">School &amp; Class <span class="text-muted fw-normal">(optional, can be added later)</span></h2>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="school_id" class="form-label">School</label>
        <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id">
            <option value="">Not sure yet</option>
            @foreach (['primary' => 'Primary Schools', 'secondary' => 'Secondary Schools'] as $type => $label)
                @if ($schools->where('school_type', $type)->isNotEmpty())
                    <optgroup label="{{ $label }}">
                        @foreach ($schools->where('school_type', $type) as $school)
                            <option value="{{ $school->id }}" @selected((string) $selectedSchoolId === (string) $school->id)>
                                {{ $school->name }}@if ($school->town) &mdash; {{ $school->town }} @endif
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            @endforeach
        </select>
        @error('school_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">
            Don't see your child's school?
            <a href="{{ route('school-registration.create') }}">Add it here</a>.
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="school_class_id" class="form-label">Class</label>
        <select class="form-select @error('school_class_id') is-invalid @enderror" id="school_class_id" name="school_class_id">
            <option value="">Select a school first</option>
        </select>
        @error('school_class_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div id="secondary-transition-fields" hidden>
    <div class="alert alert-light border">
        <p class="mb-3">
            Since your child is in 5th or 6th Class, you can tell us which secondary school they're likely
            to attend. This helps build support among the incoming group before they start 1st Year &mdash;
            but it's entirely optional.
        </p>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="transition_choice" id="transition_undecided"
                       value="undecided" @checked($selectedTransitionChoice === 'undecided')>
                <label class="form-check-label" for="transition_undecided">Not sure yet</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="transition_choice" id="transition_share"
                       value="share" @checked($selectedTransitionChoice === 'share')>
                <label class="form-check-label" for="transition_share">I can share which school</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="transition_choice" id="transition_not_stated"
                       value="not_stated" @checked($selectedTransitionChoice === 'not_stated')>
                <label class="form-check-label" for="transition_not_stated">Prefer not to say</label>
            </div>
        </div>

        <div id="transition-share-fields" class="row" hidden>
            <div class="col-md-6 mb-3 mb-md-0">
                <label for="likely_secondary_school_id" class="form-label">Likely Secondary School</label>
                <select class="form-select" id="likely_secondary_school_id" name="likely_secondary_school_id">
                    <option value="">Select a school</option>
                    @foreach ($schools->where('school_type', 'secondary') as $school)
                        <option value="{{ $school->id }}" @selected((string) $selectedSecondaryId === (string) $school->id)>
                            {{ $school->name }}@if ($school->town) &mdash; {{ $school->town }} @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="transition_status" class="form-label">How likely is this?</label>
                <select class="form-select" id="transition_status" name="transition_status">
                    <option value="considering" @selected($selectedTransitionStatus === 'considering')>Considering</option>
                    <option value="likely" @selected($selectedTransitionStatus === 'likely')>Likely</option>
                    <option value="confirmed" @selected($selectedTransitionStatus === 'confirmed')>Confirmed</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const schoolsData = @json($schoolsData);
        const transitionLevels = ['5th_class', '6th_class'];

        const schoolSelect = document.getElementById('school_id');
        const classSelect = document.getElementById('school_class_id');
        const transitionFields = document.getElementById('secondary-transition-fields');
        const transitionShareFields = document.getElementById('transition-share-fields');
        const transitionChoiceInputs = document.querySelectorAll('input[name="transition_choice"]');

        const selectedSchoolId = {{ $selectedSchoolId ? (int) $selectedSchoolId : 'null' }};
        const selectedClassId = {{ $selectedClassId ? (int) $selectedClassId : 'null' }};

        function toggleShareFields() {
            const checked = document.querySelector('input[name="transition_choice"]:checked');
            transitionShareFields.hidden = !checked || checked.value !== 'share';
        }

        transitionChoiceInputs.forEach(function (input) {
            input.addEventListener('change', toggleShareFields);
        });
        toggleShareFields();

        function populateClasses(schoolId, preselectClassId) {
            classSelect.innerHTML = '';

            const classes = schoolsData[schoolId] || [];

            if (classes.length === 0) {
                classSelect.innerHTML = '<option value="">No classes set up yet</option>';
                toggleTransitionFields(null);
                return;
            }

            classSelect.innerHTML = '<option value="">Select a class</option>';

            classes.forEach(function (cls) {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.display_name;
                option.dataset.classLevel = cls.class_level;
                if (preselectClassId && String(preselectClassId) === String(cls.id)) {
                    option.selected = true;
                }
                classSelect.appendChild(option);
            });

            const selectedOption = classSelect.options[classSelect.selectedIndex];
            toggleTransitionFields(selectedOption ? selectedOption.dataset.classLevel : null);
        }

        function toggleTransitionFields(classLevel) {
            transitionFields.hidden = !transitionLevels.includes(classLevel);
        }

        schoolSelect.addEventListener('change', function () {
            populateClasses(this.value, null);
        });

        classSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            toggleTransitionFields(selectedOption ? selectedOption.dataset.classLevel : null);
        });

        if (selectedSchoolId) {
            populateClasses(selectedSchoolId, selectedClassId);
        }
    })();

    (function () {
        const wordLists = @json($codeNameWordLists);
        const suggestionsContainer = document.getElementById('code-name-suggestions');
        const chips = Array.from(document.querySelectorAll('.code-name-chip'));
        const shuffleButton = document.getElementById('code-name-shuffle');
        const input = document.getElementById('public_label');

        function pick(list) {
            return list[Math.floor(Math.random() * list.length)];
        }

        function randomCombo() {
            return pick(wordLists.adjectives) + ' ' + pick(wordLists.colors) + ' ' + pick(wordLists.nouns);
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                input.value = chip.textContent.trim();
            });
        });

        if (shuffleButton) {
            shuffleButton.addEventListener('click', function () {
                const used = [];

                chips.forEach(function (chip) {
                    let combo = randomCombo();
                    let attempts = 0;

                    while (used.includes(combo) && attempts < 10) {
                        combo = randomCombo();
                        attempts++;
                    }

                    used.push(combo);
                    chip.textContent = combo;
                });
            });
        }
    })();
</script>
