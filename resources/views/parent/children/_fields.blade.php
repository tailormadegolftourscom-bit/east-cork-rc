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

    // 6th class answers with a single required choice: a school, or undecided.
    // A stored "not stated" from before this was required reads as unanswered.
    $selectedIntended = old('intended_secondary', match (true) {
        (bool) $link?->likely_secondary_school_id => (string) $link->likely_secondary_school_id,
        (bool) $link?->unlisted_secondary_name => 'unlisted',
        $link?->transition_status === 'considering' => 'undecided',
        default => '',
    });
    $selectedIntendedStatus = old('intended_status',
        ($link?->likely_secondary_school_id || $link?->unlisted_secondary_name) ? $link->transition_status : 'likely');
    $unlistedName = old('unlisted_secondary_name', $link?->unlisted_secondary_name);
    $secondarySchools = $schools->where('school_type', 'secondary');

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

<h2 class="h6 mb-1">Rebel Code Name</h2>
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

<h2 class="h6 mb-1">Visibility to Other Parents</h2>
<p class="text-muted small mb-3">
    Other logged-in parents can see a class-by-class breakdown of who's taking part, to help build momentum.
    You choose how much of that is your child. Both default to the more private option.
</p>

@php
    $selectedIdentityVisibility = old('identity_visibility', $child?->identity_visibility ?? 'code_name');
    $selectedClassVisibility = old('class_visibility', $child?->class_visibility ?? 'general');
@endphp

<div class="row mb-3">
    <div class="col-md-6 mb-3 mb-md-0">
        <label class="form-label d-block">Shown as</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="identity_visibility" id="identity_code_name"
                   value="code_name" @checked($selectedIdentityVisibility === 'code_name')>
            <label class="form-check-label" for="identity_code_name">Code name only</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="identity_visibility" id="identity_code_name_first_name"
                   value="code_name_first_name" @checked($selectedIdentityVisibility === 'code_name_first_name')>
            <label class="form-check-label" for="identity_code_name_first_name">Code name + first name</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label d-block">Class shown as</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="class_visibility" id="class_general"
                   value="general" @checked($selectedClassVisibility === 'general')>
            <label class="form-check-label" for="class_general">General grade only (e.g. "4th Class")</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="class_visibility" id="class_specific"
                   value="specific" @checked($selectedClassVisibility === 'specific')>
            <label class="form-check-label" for="class_specific">Specific class, if named (e.g. "Rang Lucy")</label>
        </div>
    </div>
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

<div id="sixth-class-secondary-fields" hidden>
    <div class="alert alert-light border">
        <label for="intended_secondary" class="form-label fw-semibold">
            Which secondary school does your child currently intend to go to?
        </label>
        <p class="small text-muted mb-2">
            Rebels heading to the same secondary school are put in touch before 1st Year, so nobody starts
            alone. Choose <strong>Undecided</strong> if you don't know yet &mdash; you can change it any time.
        </p>
        <div class="row g-2">
            <div class="col-md-7">
                <select class="form-select @error('intended_secondary') is-invalid @enderror"
                        id="intended_secondary" name="intended_secondary">
                    <option value="" @selected($selectedIntended === '')>Choose one&hellip;</option>
                    <option value="undecided" @selected($selectedIntended === 'undecided')>Undecided</option>
                    @foreach ($secondarySchools as $school)
                        <option value="{{ $school->id }}" @selected($selectedIntended === (string) $school->id)>
                            {{ $school->name }}@if ($school->town) &mdash; {{ $school->town }} @endif
                        </option>
                    @endforeach
                    <option value="unlisted" @selected($selectedIntended === 'unlisted')>Unlisted &mdash; I'll type the name</option>
                </select>
                @error('intended_secondary')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-5" id="intended-status-field" hidden>
                <select class="form-select" name="intended_status" aria-label="How settled is this?">
                    <option value="considering" @selected($selectedIntendedStatus === 'considering')>Still considering</option>
                    <option value="likely" @selected($selectedIntendedStatus === 'likely')>Likely</option>
                    <option value="confirmed" @selected($selectedIntendedStatus === 'confirmed')>Confirmed</option>
                </select>
            </div>
        </div>
        <div id="unlisted-secondary-fields" class="mt-3" hidden>
            <div class="row g-2">
                <div class="col-md-7">
                    <label for="unlisted_secondary_name" class="form-label small mb-1">Secondary school name</label>
                    <input type="text" class="form-control @error('unlisted_secondary_name') is-invalid @enderror"
                           id="unlisted_secondary_name" name="unlisted_secondary_name" maxlength="150"
                           value="{{ $unlistedName }}">
                    @error('unlisted_secondary_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-5">
                    <label for="unlisted_secondary_town" class="form-label small mb-1">
                        Town <span class="text-muted">(optional)</span>
                    </label>
                    <input type="text" class="form-control" id="unlisted_secondary_town" maxlength="100">
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="request-secondary-button"
                    data-url="{{ route('parent.secondary-school-request') }}">
                Request it be added as a secondary school
            </button>
            <div class="small mt-2" id="request-secondary-result" role="status"></div>
        </div>

        <div class="form-text">
            Secondary school not in the list? Choose <strong>Unlisted</strong>, type its name, and ask for it to
            be added.
        </div>
    </div>
</div>

<div id="secondary-transition-fields" hidden>
    <div class="alert alert-light border">
        <p class="mb-3">
            Since your child is in 5th Class, you can tell us which secondary school they're likely
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

        const schoolSelect = document.getElementById('school_id');
        const classSelect = document.getElementById('school_class_id');
        const transitionFields = document.getElementById('secondary-transition-fields');
        const sixthClassFields = document.getElementById('sixth-class-secondary-fields');
        const intendedSelect = document.getElementById('intended_secondary');
        const intendedStatusField = document.getElementById('intended-status-field');

        const unlistedFields = document.getElementById('unlisted-secondary-fields');
        const unlistedName = document.getElementById('unlisted_secondary_name');
        const unlistedTown = document.getElementById('unlisted_secondary_town');
        const requestButton = document.getElementById('request-secondary-button');
        const requestResult = document.getElementById('request-secondary-result');

        function toggleIntendedStatus() {
            intendedStatusField.hidden = !intendedSelect.value || intendedSelect.value === 'undecided';
            unlistedFields.hidden = intendedSelect.value !== 'unlisted';
            unlistedName.required = !sixthClassFields.hidden && intendedSelect.value === 'unlisted';
        }

        intendedSelect.addEventListener('change', toggleIntendedStatus);
        toggleIntendedStatus();

        // Files the request without leaving the page, so a half-filled form
        // is not lost. Saving the child still records the typed name either way.
        requestButton.addEventListener('click', function () {
            const name = unlistedName.value.trim();

            if (!name) {
                requestResult.className = 'small mt-2 text-danger';
                requestResult.textContent = 'Type the school\'s name first.';
                unlistedName.focus();
                return;
            }

            requestButton.disabled = true;
            requestResult.className = 'small mt-2 text-muted';
            requestResult.textContent = 'Sending…';

            fetch(requestButton.dataset.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ school_name: name, town: unlistedTown.value.trim() }),
            })
                .then(function (response) {
                    return response.json().then(function (data) { return { ok: response.ok, data: data }; });
                })
                .then(function (result) {
                    const message = result.data.message
                        || (result.data.errors && Object.values(result.data.errors)[0][0])
                        || 'Something went wrong. Please try again.';
                    requestResult.className = 'small mt-2 ' + (result.ok ? 'text-success' : 'text-danger');
                    requestResult.textContent = message;
                    requestButton.disabled = result.ok;
                })
                .catch(function () {
                    requestResult.className = 'small mt-2 text-danger';
                    requestResult.textContent = 'Could not send the request. Please try again.';
                    requestButton.disabled = false;
                });
        });
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

        // 6th class must answer (a school or Undecided); 5th class may.
        function toggleTransitionFields(classLevel) {
            const isSixth = classLevel === '6th_class';
            sixthClassFields.hidden = !isSixth;
            intendedSelect.required = isSixth;
            toggleIntendedStatus();
            transitionFields.hidden = classLevel !== '5th_class';
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
