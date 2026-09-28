@extends('layouts.app')

@php($pageTitle = 'For Kids: Gen Alpha Rebels — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="mb-5" style="max-width: 46rem;">
            <span class="badge text-bg-warning mb-3">For Kids</span>
            <h1 class="display-6 fw-bold mb-3">Welcome, Gen Alpha Rebel</h1>
            <p class="lead text-muted">
                Your mum, dad or another grown-up probably found this website first. But this page is for you,
                because
                <span class="d-inline-block fw-bolder fs-4 px-3 py-2 mt-2 rounded-3 shadow-sm"
                      style="background: #e8a33d; color: #1c2b22;">you're the most important person here! &#x2694;&#xFE0F;&#x1F9B8;&#x1F981;</span>
            </p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">What's a Gen Alpha Rebel?</h2>
                        <p class="text-muted mb-0">
                            The name comes from <em>The Amazing Generation</em>, a book for kids by Jonathan Haidt
                            and Catherine Price. Rebels are kids who decide to run their own lives: real friends,
                            real freedom, real fun &mdash; and technology used as a tool, instead of technology
                            using them. Here in East Cork, a Rebel is any kid whose family has joined in. We treat Rebels as heroes and
                            want to encourage them in any way we can.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Who are Rebels up against?</h2>
                        <p class="text-muted mb-0">
                            The <strong>Greedy Wizards</strong>: the companies that design apps and games to keep
                            you scrolling for as long as possible, because the longer you stay, the more money they
                            make. Some of the cleverest people in the world work on making those apps hard to put
                            down. Rebels outsmart them by doing something better with their time &mdash; together.
                            Our quarrel is with the Wizards, never with other children.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">Other kids aren't the enemy</h2>
                <p class="text-muted mb-3">
                    This is really important. Kids in your class who already have social media aren't the enemy
                    &mdash; they're up against the same Greedy Wizards you are, and some of them would love to join
                    you.
                </p>
                <p class="text-muted mb-0">
                    And a phone for calls and texts is fine. This is about holding off on social media, not about
                    taking anything away.
                </p>
            </div>
        </div>

        <div class="mb-5">
            <h2 class="h4 mb-3">Things Rebels are doing</h2>
            <p class="text-muted mb-4">
                Here's the secret grown-ups already know: give a group of kids an open space and each other, and
                you won't hear from them for hours. No app required.
            </p>
            @include('partials.activity-list', ['limit' => 3])
            <p class="mt-3 mb-0"><a href="{{ route('activities') }}">See everything that's on &rarr;</a></p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Your own Rebel code name</h3>
                        <p class="small text-muted mb-0">
                            Your real name is never shown on this website. You choose a code name instead, like
                            <strong>Shiny Blue Crocodile</strong>. It's the one part that's 100% up to you. It
                            earns a badge the longer you're a Rebel: "Just Joined", then "3 Months In",
                            "6 Months In" and beyond.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">A big end-of-year party</h3>
                        <p class="small text-muted mb-0">
                            A party for every Rebel hero in 4th, 5th and 6th class who sticks with it &mdash; a
                            real reward for you and your family.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Starting secondary school with friends</h3>
                        <p class="small text-muted mb-0">
                            Rebels going to the same secondary school get together before 1st Year, so you already
                            know you're not walking in alone.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5" id="your-information">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">Your information</h2>
                <p class="text-muted mb-2">
                    When your mum, dad or another grown-up signs you up, this website keeps your first name (and
                    sometimes your surname), your Rebel code name, your school and class, and which activities you're
                    signed up for. That's so we can see how many Rebels are in each class and get you together with
                    other Rebels.
                </p>
                <p class="text-muted mb-2">
                    Other people on the website only see your code name &mdash; not your real name, unless your
                    grown-up says it's OK for other parents to see your first name. Your surname is never shown to
                    anyone.
                </p>
                <p class="text-muted mb-0">
                    It's your information. If you want to see it, change it, or have it deleted, you or your grown-up
                    can email <a href="mailto:info@eastcorkreclaimchildhood.ie">info@eastcorkreclaimchildhood.ie</a>
                    and it will be sorted. Grown-ups can read the full
                    <a href="{{ route('privacy') }}">Privacy &amp; Data Protection Notice</a>.
                </p>
            </div>
        </div>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="h4 mb-2">Want to be a Rebel?</h2>
                <p class="text-muted mb-0">
                    Show this page to your mum, dad or whoever looks after you. It takes them a couple of minutes
                    to sign you up &mdash; and then you pick your code name.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('parents') }}" class="btn btn-primary btn-lg">Show a Grown-Up</a>
            </div>
        </div>
    </section>
@endsection
