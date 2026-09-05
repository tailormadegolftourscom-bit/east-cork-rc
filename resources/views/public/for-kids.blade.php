@extends('layouts.app')

@php($pageTitle = 'For Kids — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="mb-5" style="max-width: 46rem;">
            <h1 class="display-6 fw-bold mb-3">Hey — this page is for you!</h1>
            <p class="lead text-muted">
                Your mum, dad, or another grown-up probably found this website first. But this bit is written
                for you, because honestly, you're the most important person here.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">So what's actually going on?</h2>
                        <p class="text-muted mb-0">
                            Loads of parents in East Cork have noticed the same thing: everyone feels like they
                            have to get social media really young, just because it seems like everyone else has
                            it. So a group of parents decided to team up and wait a bit longer together — as a
                            whole class or school, not just one family on their own. That way nobody has to feel
                            like the odd one out.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">This isn't about taking your phone away</h2>
                        <p class="text-muted mb-0">
                            Loads of kids in this together still have a phone for calls, texts, and group chats
                            with friends — just without the social media apps for now. It's about holding off on
                            one specific thing, not about missing out on everything.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">You get to pick your own code name</h2>
                <p class="text-muted mb-3">
                    If your family joins in, your real name is never shown anywhere on this website — instead,
                    you get to choose your own code name, like <strong>Shiny Blue Crocodile</strong> or
                    <strong>Sweet Red Carnation</strong>. Pick something fun, or ask a grown-up to shuffle through
                    ideas with you until you find one you like.
                </p>
                <p class="text-muted mb-0">
                    It's the one part of all this that's completely, 100% up to you.
                </p>
            </div>
        </div>

        <div class="mb-5">
            <h2 class="h4 mb-3">The fun stuff that's part of this too</h2>
            <p class="text-muted mb-4">
                Waiting on social media is only half of it — the other half is doing things that are actually
                more fun instead.
            </p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Meeting up to play</h3>
                            <p class="small text-muted mb-0">Kickarounds, bike spins, badminton, playground
                                meetups — just turning up and messing about with friends.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Outdoors &amp; exploring</h3>
                            <p class="small text-muted mb-0">Nature walks, treasure hunts, and the woods, coast
                                and countryside that are already right on your doorstep.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">A big end-of-year party</h3>
                            <p class="small text-muted mb-0">A proper BBQ or disco for every 6th class who held
                                off together — a real thank-you, not just words.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Starting secondary school with friends</h3>
                            <p class="small text-muted mb-0">A meetup before 1st year, so you already know
                                you're not walking in alone.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="h4 mb-2">Want your family to join in?</h2>
                <p class="text-muted mb-0">
                    Show this page to your mum, dad, or whoever looks after you. It only takes them a couple of
                    minutes to sign up.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('parents') }}" class="btn btn-primary btn-lg">Show a Grown-Up</a>
            </div>
        </div>
    </section>
@endsection
