@extends('layouts.app')

@php($pageTitle = 'About — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">About ECRC</h1>
        <p class="lead text-muted mb-4" style="max-width: 46rem;">
            I'm <strong>Peter O'Sullivan</strong>, interim convenor of East Cork Reclaim Childhood and a parent of
            children in <strong>6th Class and 5th Class at Midleton Gaelscoil</strong>.
        </p>

        <p class="fs-5 mb-5" style="max-width: 46rem;">
            The aim is simple: help children <strong>delay social media access</strong> for as long as possible
            into secondary school, knowing that other children in their class are doing the same.
        </p>

        <div class="mb-5" style="max-width: 46rem;">
            <h2 class="h4 mb-3">Why ECRC</h2>
            <p class="text-muted">
                Like many parents, I have become increasingly concerned about the effect that early access to
                social media can have on children &mdash; and about how difficult it is for individual families to
                hold the line when children believe that everyone else already has access.
            </p>
            <p class="text-muted">
                East Cork Reclaim Childhood is an extension of the excellent work already being done nationally by
                Smartphone Free Childhood Ireland (SFCI) and others, and locally by the East Cork Smartphone Free Childhood Group and the school reps.
            </p>
            <p class="text-muted">
                I was particularly inspired by the work of the <strong>East Cork Smartphone Free Childhood
                Group</strong>. The stories and experiences shared by parents through the wider
                <a href="https://smartphonefree.ie/" target="_blank" rel="noopener"><strong>Smartphone Free Childhood
                Ireland</strong></a> community have reinforced for me just how important it is to act before these
                pressures become established.
            </p>
            <p class="text-muted mb-0">
                But it is parents who must take the lead. Governments can regulate and schools can support,
                but neither can make these choices for families. Parents can. And, above all, one of the most striking things
                about the very successful <em>It Takes a Village</em> initiative was its poster showing people
                <strong>physically together</strong>. That gets to the heart of Reclaim Childhood: parents supporting
                parents, children spending time together in the real world, and communities making it easier for families to
                choose a different path.
            </p>
        </div>

        <div class="card border-warning shadow-sm mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-3">The solution has to happen locally</h2>
                <p class="fs-5 fw-semibold mb-3">Parents need to mobilise together.</p>
                <p class="text-muted mb-0">
                    If enough parents in the same class, school and community make the same decision, delaying
                    social media becomes much easier for everyone &mdash; particularly for the children themselves.
                </p>
            </div>
        </div>

        <div class="mb-4" style="max-width: 46rem;">
            <h2 class="h4 mb-3">More than delaying social media</h2>
            <p class="text-muted mb-0">
                ECRC is about supporting the children who make that choice, helping parents support one another,
                and creating more opportunities for children to meet, play, take part in activities and enjoy their
                free time away from screens.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">Kids need encouragement, not just rules</h3>
                        <p class="text-muted mb-0">
                            Telling a child "no social media" and leaving it there doesn't work. What works is
                            encouragement: showing kids they're part of something, that other kids are doing it
                            too, and that they're doing something brave and worthwhile. That's why our kids are
                            <strong>Gen Alpha Rebels</strong> &mdash; the name comes from Jonathan Haidt and
                            Catherine Price's <em>The Amazing Generation</em>. Rebels take on the Greedy Wizards who build apps to hook them. Our quarrel is with the Wizards, never with other children.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">Replace screens with activity</h3>
                        <p class="text-muted mb-0">
                            Taking a screen away leaves a gap, and something has to fill it. The answer
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
                <h2 class="h4 mb-4">How it might work</h2>
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
                        <h2 class="h5 mb-3">Help shape what it becomes</h2>
                        <p class="text-muted mb-0">
                            The project is still at an early stage. <strong>The ideas on this website are a starting point,
                            not a finished programme</strong>. Parents, schools and others across East Cork are invited
                            to help shape what it becomes. Any group of parents who'd like to talk it over can
                            get in touch to arrange a meeting.
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

        <div class="mb-5">
            <div class="fw-bold">Peter O'Sullivan</div>
            <div class="fst-italic">Interim Convenor</div>
            <div class="text-muted">East Cork Reclaim Childhood</div>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Register Your Rebel</a>
            <a href="mailto:info@eastcorkreclaimchildhood.ie" class="btn btn-outline-primary btn-lg">Get in Touch</a>
        </div>
    </section>
@endsection
