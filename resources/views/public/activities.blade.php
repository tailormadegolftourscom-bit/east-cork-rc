@extends('layouts.app')

@php($pageTitle = 'Reclaim Free Time — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">Reclaim Free Time</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            Holding off on social media is only half the story. The other half is replacing screen time with
            something better: getting kids out, active and together. None of this needs to be a formal club with
            a coach running drills. Put a group of kids together in an open space and they'll happily amuse
            themselves for hours.
        </p>

        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
                <h2 class="h4 mb-0">What's on</h2>
                <span class="small text-muted">
                    <span class="badge text-bg-success">Planned</span> happening &middot;
                    <span class="badge text-bg-secondary">Suggested</span> looking for someone to run it
                </span>
            </div>
            @include('partials.activity-list')
        </div>

        @if ($venues->isNotEmpty())
            <div class="card border-0 bg-light mb-5">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h5 mb-3">Venues</h2>
                    <p class="text-muted mb-3">Places we're using, or hoping to use, for Rebel activities.</p>
                    <ul class="mb-0">
                        @foreach ($venues as $venue)
                            <li>
                                @if ($venue->map_url)
                                    <a href="{{ $venue->map_url }}" target="_blank" rel="noopener">{{ $venue->label }}</a>
                                @else
                                    {{ $venue->label }}
                                @endif
                                @if ($venue->description)<span class="text-muted small"> &mdash; {{ $venue->description }}</span>@endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Got an idea?</h2>
                        <p class="text-muted mb-3">
                            A kickaround, a walk, a board-games afternoon in a local hall &mdash; suggest it and
                            we'll list it. Ideas from kids are especially welcome.
                        </p>
                        <a href="mailto:info@eastcorkreclaimchildhood.ie?subject=Activity%20idea" class="btn btn-outline-primary">Suggest an Activity</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Can you lend a hand?</h2>
                        <p class="text-muted mb-3">
                            Every activity needs a grown-up or two to turn up and open the door. You don't need to
                            be a parent here to help.
                        </p>
                        <a href="{{ route('supporters.create') }}" class="btn btn-outline-primary">Join as a Supporter</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h2 class="h4 mb-3">Something to look forward to</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">An end-of-year party</h3>
                            <p class="small text-muted mb-0">
                                For the Rebels in 4th, 5th and 6th class, run for the whole region or school by
                                school &mdash; a real celebration of sticking together.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Transition groups</h3>
                            <p class="small text-muted mb-0">
                                Rebels heading to the same secondary school meet before 1st Year, so they arrive
                                already knowing they've got company.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">A badge that grows over time</h3>
                            <p class="small text-muted mb-0">
                                Every Rebel earns a badge next to their code name &mdash; "3 Months In",
                                "6 Months In" and onward. No real names needed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Register Your Rebel</a>
        </div>
    </section>
@endsection
