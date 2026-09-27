@extends('layouts.app')

@php($pageTitle = 'About — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">About ECRC</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            I'm Peter O'Sullivan, interim convenor of East Cork Reclaim Childhood. This is why I started it, and
            how I think it can work.
        </p>

        <div class="card border-warning shadow-sm mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">Why now</h2>
                <p class="text-muted mb-0">
                    Social media arrives in a class one family at a time, each giving in because they think
                    everyone else already has. The only thing that stops it is parents deciding together, early
                    &mdash; and that means now, not next year. Register your child as a Rebel today, then tell one
                    other parent in their class.
                </p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Kids need encouragement, not just rules</h2>
                        <p class="text-muted mb-0">
                            Telling a child "no social media" and leaving it there doesn't work. What works is
                            encouragement: showing kids they're part of something, that other kids are doing it
                            too, and that they're doing something brave and worthwhile. That's why our kids are
                            <strong>Gen Alpha Rebels</strong> &mdash; the name comes from Jonathan Haidt and
                            Catherine Price's <em>The Amazing Generation</em>. Rebels take on the Greedy Wizards who
                            build apps to hook them, never their fellow students.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Replace screens with activity</h2>
                        <p class="text-muted mb-0">
                            Taking a screen away leaves a gap, and something has to fill it. I believe the answer
                            is simple: get kids out, active and together. Badminton, a cycle on the Greenway, a
                            game in a local hall. Put a group of kids together and they'll do the rest.
                            <a href="{{ route('activities') }}">See what's on</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-4">How I see it working</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="display-6 fw-bold text-primary mb-2">1</div>
                        <h3 class="h6">Start in 4th class</h3>
                        <p class="small text-muted mb-0">
                            Engage kids and their classmates as Gen Alpha Rebels from 4th class, before the
                            pressure for social media really starts. A group that starts together holds together.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="display-6 fw-bold text-primary mb-2">2</div>
                        <h3 class="h6">Celebrate every year</h3>
                        <p class="small text-muted mb-0">
                            An end-of-year party for the Rebels in 4th, 5th and 6th class, run either for the
                            whole region or school by school, whichever gets more kids there.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="display-6 fw-bold text-primary mb-2">3</div>
                        <h3 class="h6">Carry it into secondary</h3>
                        <p class="small text-muted mb-0">
                            Transition groups for Rebels going on to the same secondary school, so they walk into
                            1st Year already knowing they're not on their own.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Parent-led</h2>
                        <p class="text-muted mb-0">
                            This is run by parents and supporters, not by schools. Schools are welcome to help,
                            but none is ever asked to take part, and parents can organise regardless.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Happy to talk it through</h2>
                        <p class="text-muted mb-0">
                            These are ideas, not rules. I'm glad to host a meeting or workshop with any group of
                            parents who'd like to talk them over &mdash; just get in touch.
                        </p>
                        @php($meetings = collect(config('notice.workshops', []))->filter(fn ($w) => $w['date'] >= today()->toDateString()))
                        @if ($meetings->isNotEmpty())
                            <p class="text-muted mt-3 mb-0">
                                Informal meetings at {{ config('notice.venue') }}:
                                {{ $meetings->pluck('label')->implode(' and ') }}.
                                <a href="{{ route('workshops.rsvp') }}">RSVP here</a>.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Register Your Rebel</a>
            <a href="mailto:info@eastcorkreclaimchildhood.ie" class="btn btn-outline-primary btn-lg">Get in Touch</a>
        </div>
    </section>
@endsection
