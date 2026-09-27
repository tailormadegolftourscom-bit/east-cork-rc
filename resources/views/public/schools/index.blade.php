@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="mb-4">
            <h1 class="display-6 fw-bold mb-3">Schools in East Cork</h1>
            <p class="lead text-muted mb-3">
                These are the schools currently active in the East Cork Reclaim Childhood network.
            </p>
            <p class="text-muted mb-0">
                This is a parent-led initiative. A school may appear here even if its own support status is still undecided.
                Where a school has chosen to support the initiative, that is shown below.
            </p>
        </div>

        @php
            $primarySchools = $schools->get('primary', collect());
            $secondarySchools = $schools->get('secondary', collect());
        @endphp

        @if ($primarySchools->isEmpty() && $secondarySchools->isEmpty())
            <div class="alert alert-warning">
                <h2 class="h5">No schools are listed yet</h2>
                <p>
                    We're working through the East Cork schools now and they'll appear here as they're added.
                    You don't have to wait for yours — you can register your children today and attach them to a
                    school later.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register') }}" class="btn btn-primary">Register My Children</a>
                    <a href="{{ route('school-registration.create') }}" class="btn btn-outline-primary">
                        Tell Us About My School
                    </a>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-primary">{{ $primarySchools->count() }}</div>
                        <div class="text-muted">Active primary schools</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-primary">{{ $secondarySchools->count() }}</div>
                        <div class="text-muted">Active secondary schools</div>
                    </div>
                </div>
            </div>
        </div>

        @if ($primarySchools->isNotEmpty() || $secondarySchools->isNotEmpty())
            <div class="mb-5">
                <label for="school-search" class="form-label h5">Find your school</label>
                <input type="search" id="school-search" class="form-control form-control-lg"
                       placeholder="Search by school name or town&hellip;" aria-label="Search for your school">
                <div class="form-text">
                    See how many families at each school are already part of this, class by class.
                    @guest
                        <a href="{{ route('login') }}">Log in</a> once you've joined to see each class by code
                        name instead of just a number.
                    @endguest
                </div>
                <p id="school-search-empty" class="text-muted mt-3 mb-0" hidden>
                    No schools match your search.
                    <a href="{{ route('school-registration.create') }}">Add your school</a>.
                </p>
            </div>
        @endif

        <div class="mb-5" data-school-section>
            <h2 class="h3 mb-3">Primary Schools</h2>

            @if ($primarySchools->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="mb-0 text-muted">No active primary schools are listed yet.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($primarySchools as $school)
                        @include('public.schools._school-card', ['school' => $school])
                    @endforeach
                </div>
            @endif
        </div>

        <div data-school-section>
            <h2 class="h3 mb-3">Secondary Schools</h2>

            @if ($secondarySchools->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="mb-0 text-muted">No active secondary schools are listed yet.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($secondarySchools as $school)
                        @include('public.schools._school-card', ['school' => $school])
                    @endforeach
                </div>
            @endif
        </div>

        <script>
            (function () {
                const input = document.getElementById('school-search');
                if (!input) return;

                const cards = Array.from(document.querySelectorAll('[data-school-search]'));
                const sections = Array.from(document.querySelectorAll('[data-school-section]'));
                const emptyMessage = document.getElementById('school-search-empty');

                input.addEventListener('input', function () {
                    const term = this.value.trim().toLowerCase();
                    let visibleCount = 0;

                    cards.forEach(function (card) {
                        const matches = card.dataset.schoolSearch.includes(term);
                        card.hidden = !matches;
                        if (matches) visibleCount++;
                    });

                    // Hide a Primary or Secondary heading with nothing under it.
                    sections.forEach(function (section) {
                        const cardsHere = section.querySelectorAll('[data-school-search]');
                        section.hidden = cardsHere.length > 0
                            && Array.from(cardsHere).every(function (card) { return card.hidden; });
                    });

                    emptyMessage.hidden = visibleCount !== 0;
                });
            })();
        </script>
    </section>
@endsection
