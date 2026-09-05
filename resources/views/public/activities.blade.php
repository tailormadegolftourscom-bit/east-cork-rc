@extends('layouts.app')

@php($pageTitle = 'Reclaim Free Time — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">Reclaim Free Time</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            Holding off on social media is only half the story. The other half — arguably the more important half —
            is giving kids something better to fill that time with: outdoor play, real friendships, and things to
            actually look forward to. None of this needs to be a formal club with a coach running drills — the point
            is kids meeting up, chatting, and messing about together.
        </p>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6">Outdoors &amp; nature</h2>
                        <p class="small text-muted mb-0">Nature walks, treasure hunts, cycling, and the woods, coast
                            and countryside East Cork already has plenty of.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6">Sport &amp; games</h2>
                        <p class="small text-muted mb-0">Kickarounds, badminton, playground meetups — just kids
                            turning up to play together, not a training session with a coach.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6">Indoors, together</h2>
                        <p class="small text-muted mb-0">Board games and local hall activities for evenings, weekends
                            and rainy days — screen-free but still social.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h2 class="h4 mb-3">Marking the milestones</h2>
            <p class="text-muted mb-4">
                Two moments worth making a real occasion of:
            </p>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">An end-of-year celebration</h3>
                            <p class="small text-muted mb-0">
                                A proper BBQ, disco or party for every 6th class family who held off through primary
                                school — a real thank-you for a whole school or class, not just a pledge on a page.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">A pre-1st-year meetup</h3>
                            <p class="small text-muted mb-0">
                                A get-together before September for the families carrying it into secondary school —
                                so the kids arrive already knowing they've got company.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">Where this is heading</h2>
                <p class="text-muted mb-3">
                    We want to build proper tools for this: a way to list activities, find volunteers willing to
                    supervise or lead a session, and match up venues — community halls, sports halls, pitches and
                    playgrounds — with families looking for something to do. Longer term, we'd love to see this grow
                    into proper local youth clubs. That's a bigger piece of work, and we're building it after the
                    core parent, child and school features are solid, so it's worth doing properly rather than rushing.
                </p>
                <p class="text-muted mb-0">
                    In the meantime, the same class and school WhatsApp groups that help parents hold off on social
                    media together are a good place to start organising something informally — a kickaround after
                    school, a weekend walk, a board games evening. You don't need to wait for us to build a feature to
                    start reclaiming free time.
                </p>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('parents') }}" class="btn btn-primary btn-lg">Get Started as a Parent</a>
        </div>
    </section>
@endsection
