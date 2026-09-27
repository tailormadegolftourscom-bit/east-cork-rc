@extends('layouts.app')

@php($pageTitle = 'For Parents — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="row align-items-center g-4 mb-5">
            <div class="col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Don't wait until your child is the last one without it</h1>
                <p class="lead text-muted">
                    Social media usually arrives in a class one family at a time, each giving in because they
                    assume everyone else already has. You can stop that happening in your child's class, but only
                    if parents act together, and early. A simple phone for calls and texts in the meantime is
                    no problem at all.
                </p>
                <p class="fw-semibold mb-0">
                    Register your child as a Gen Alpha Rebel today. Then tell one other parent in the class.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-grid gap-2">
                    @auth
                        <a href="{{ route('parent.dashboard') }}" class="btn btn-primary btn-lg">Go to My Account</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Register Your Rebel Now</a>
                    @endauth
                    <a href="{{ route('about') }}" class="btn btn-outline-primary btn-lg">More Information</a>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-1">Find your child's school</h2>
                    <p class="text-muted mb-0">
                        See how many families at each school are already part of this, class by class.
                    </p>
                </div>
                <a href="{{ route('schools.index') }}" class="btn btn-primary text-nowrap">East Cork Schools</a>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Why doing this together works</h2>
                        <p class="text-muted mb-0">
                            A lot of the pressure to get a child onto social media early comes from feeling like
                            everyone else already is. When a group of parents in the same class or school agree to
                            hold off together — for as long as reasonably possible, some of us aiming as late as 16 —
                            that pressure drops for everyone, kids included. Nobody wants to be the only one left out
                            of the group chat, but nobody minds much if half the class is in the same boat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Building momentum from 4th class onward</h2>
                        <p class="text-muted mb-0">
                            The years before secondary school matter most. If enough parents in 4th, 5th and 6th class
                            agree to delay together, that group carries its own momentum into 1st year — meaning kids
                            start secondary school already surrounded by friends taking the same approach, rather than
                            starting from scratch.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">What joining actually involves</h2>
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">1. Register</div>
                        <p class="small text-muted mb-0">A couple of minutes, your email and a password.</p>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">2. Verify your email</div>
                        <p class="small text-muted mb-0">Keeps accounts genuine and secure.</p>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">3. Set your preferences</div>
                        <p class="small text-muted mb-0">How we contact you, and whether you'd rather appear publicly
                            under your real name or an anonymous supporter code.</p>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">4. Add your child (optional)</div>
                        <p class="small text-muted mb-0">Link them to their school and class so momentum can build
                            where it matters — you can always do this later.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Connecting with other parents</h2>
                        <p class="text-muted mb-0">
                            Once a few families at your child's school have joined, the natural next step is usually a
                            small class or school WhatsApp group — parent to parent, nothing formal. The site helps you
                            see where support is building; what you do with that is up to you and the other parents.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Beyond just saying no</h2>
                        <p class="text-muted mb-0">
                            Holding off on social media works best alongside a real answer to "what do I do instead?"
                            A "balance" phone covers calls, texts and safety without the social media. See
                            <a href="{{ route('resources') }}">Resources</a> for that, and
                            <a href="{{ route('activities') }}">Reclaim Free Time</a> for the friendships, play and
                            get-togethers side of it.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">Some of what parents are already doing together</h2>
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">Reading together</div>
                        <p class="small text-muted mb-0">Jonathan Haidt's <em>The Anxious Generation</em> as a class
                            or school reading group, then a chat about it.</p>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">An end-of-year celebration</div>
                        <p class="small text-muted mb-0">A BBQ, disco or party for every 6th class family who stuck
                            with it — a real thank-you.</p>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">A pre-1st-year meetup</div>
                        <p class="small text-muted mb-0">So kids carrying it into secondary school already know
                            they're not the only ones.</p>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-semibold mb-1">Just meeting up to play</div>
                        <p class="small text-muted mb-0">Badminton, a kickaround, a bike spin — no coach, no
                            training session, just kids together.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            @auth
                <a href="{{ route('parent.dashboard') }}" class="btn btn-primary btn-lg">Go to My Account</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Join as a Parent</a>
                <a href="{{ route('schools.index') }}" class="btn btn-outline-primary btn-lg">See Schools in East Cork</a>
            @endauth
        </div>
    </section>
@endsection
