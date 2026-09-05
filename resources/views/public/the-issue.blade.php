@extends('layouts.app')

@php($pageTitle = 'The Issue — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">The Issue</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            We're not against technology. We're for childhood — outdoor play, real friendships, boredom that turns
            into imagination, and the everyday independence kids need to grow up. Smartphones and social media, given
            to children too early, tend to crowd all of that out. Here's the problem as we see it, what we think
            actually helps, and what the evidence says so far.
        </p>

        <div class="d-flex gap-3 mb-5 flex-wrap">
            <a href="#problem" class="btn btn-outline-secondary btn-sm">The Problem</a>
            <a href="#solution" class="btn btn-outline-secondary btn-sm">The Solution</a>
            <a href="#evidence" class="btn btn-outline-secondary btn-sm">The Evidence</a>
        </div>

        <div id="problem" class="mb-5">
            <h2 class="h3 mb-3">The Problem</h2>
            <p class="text-muted mb-4">
                A smartphone in a child's pocket, especially one with open access to social media, changes childhood
                in ways most of us didn't sign up for:
            </p>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Less real childhood</h3>
                            <p class="small text-muted mb-0">Hours that could be outdoors, playing, reading or bored
                                enough to invent a game, spent scrolling instead.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Content no child should see</h3>
                            <p class="small text-muted mb-0">Algorithmic feeds that don't distinguish between an adult
                                and a ten-year-old, and can surface violent, sexual or otherwise harmful material fast.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Pressure that doesn't switch off</h3>
                            <p class="small text-muted mb-0">Social comparison, notifications and group chats that follow
                                a child home from school and into their bedroom.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h6">Sleep, attention and mood</h3>
                            <p class="small text-muted mb-0">Late-night use, constant interruption, and apps designed
                                to be hard to put down all take a toll on rest and concentration.</p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-muted mt-4 mb-0">
                None of this is a judgment on any individual family. Most parents who hand over a smartphone at ten or
                eleven do it reluctantly, often because it feels like everyone else's child already has one. That's
                exactly the pressure this initiative is designed to relieve.
            </p>
        </div>

        <div id="solution" class="mb-5">
            <h2 class="h3 mb-3">The Solution</h2>
            <p class="text-muted mb-4">
                We don't think any single family can fix this alone, and we don't think guilt-tripping parents helps
                either. What actually seems to work is collective, practical action:
            </p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="display-6 fw-bold text-primary mb-2">1</div>
                            <h3 class="h6">Delay together</h3>
                            <p class="small text-muted mb-0">
                                Our minimum shared goal: delay access to addictive social media for as long as
                                reasonably possible, ideally well into secondary school — as a group, not alone.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="display-6 fw-bold text-primary mb-2">2</div>
                            <h3 class="h6">Build real alternatives</h3>
                            <p class="small text-muted mb-0">
                                Delaying only works if there's something to say yes to instead — outdoor time, sport,
                                hobbies, and simple ways for kids to spend time together in person.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="display-6 fw-bold text-primary mb-2">3</div>
                            <h3 class="h6">Welcome, don't require, school support</h3>
                            <p class="small text-muted mb-0">
                                This is parent-led. Schools that want to help by sharing information or backing the
                                initiative are very welcome — but no family has to wait for a school's permission to
                                get started.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-muted mt-4 mb-0">
                For families who do want a phone for contact and safety before their child is ready for a full
                smartphone, simpler "balance" devices without app stores or social media are a practical middle step
                — see <a href="{{ route('resources') }}">Resources</a>.
            </p>
        </div>

        <div id="evidence" class="mb-3">
            <h2 class="h3 mb-3">The Evidence</h2>
            <p class="text-muted mb-4">
                A growing body of research and clinical opinion — including work like Jonathan Haidt's <em>The Anxious
                Generation</em>, and guidance from child psychologists and public health bodies internationally — links
                early smartphone and social media use with declines in sleep, attention, in-person friendship, and
                measures of teenage mental health. Researchers are still debating exactly how much of this is caused
                by phones themselves versus everything else going on in a child's life, and we try to be honest about
                that uncertainty rather than overstate it.
            </p>
            <p class="text-muted mb-0">
                What we're confident about is simpler: children consistently do better with more outdoor play, more
                unsupervised free time, more face-to-face friendship, and more sleep — and a smartphone in the bedroom
                tends to crowd all four of those out. As we build out this section, we'll add specific, sourced
                references here rather than general claims. If you're a parent, teacher or researcher with good
                sources to suggest, we'd welcome them.
            </p>
        </div>
    </section>
@endsection
