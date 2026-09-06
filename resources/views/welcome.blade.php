@extends('layouts.app')

@php($pageTitle = 'East Cork Reclaim Childhood')

@section('content')
    <section class="py-5 py-lg-6">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge text-bg-warning mb-3">Parent-led · East Cork</span>
                <h1 class="display-5 fw-bold mb-3">Want to delay your child's social media? You're in the right place.</h1>
                <p class="lead text-muted mb-4">
                    Join families across East Cork choosing to delay social media together, for as long as it
                    takes — some of us aiming as late as 16. A phone for calls and texts is no problem; it's the
                    addictive apps we're holding off on, together.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('schools.index') }}" class="btn btn-primary btn-lg">Find My School &rarr;</a>
                </div>

                <div class="d-flex flex-wrap gap-3 small">
                    <a href="{{ route('parents') }}" class="text-decoration-none">For Parents</a>
                    <a href="{{ route('for-kids') }}" class="text-decoration-none">For Kids</a>
                    <a href="{{ route('school-registration.create') }}" class="text-decoration-none">Add My School</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h5 mb-2">
                            {{ $stats['children_registered'] }} {{ Str::plural('Child', $stats['children_registered']) }}
                            in East Cork across {{ $stats['schools_primary'] }} Primary and
                            {{ $stats['schools_secondary'] }} Secondary Schools
                        </h2>
                        <p class="text-muted small mb-4">
                            Some schools will begin with parent-led support only. Others may later choose to
                            support the initiative directly. Real numbers, updated as they grow.
                        </p>

                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="h3 fw-bold text-primary mb-0">{{ $stats['schools_listed'] }}</div>
                                    <div class="small text-muted">Schools listed</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="h3 fw-bold text-primary mb-0">{{ $stats['schools_supporting'] }}</div>
                                    <div class="small text-muted">Schools Supporting</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="h3 fw-bold text-primary mb-0">{{ $stats['parents_registered'] }}</div>
                                    <div class="small text-muted">Parents registered</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="h3 fw-bold text-primary mb-0">{{ $stats['children_registered'] }}</div>
                                    <div class="small text-muted">Children registered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">For Parents</h2>
                        <p class="text-muted">
                            Find the other parents at your child's school who feel the same way you do. Add your
                            child's class securely, see who else is holding the line, and take some of the pressure
                            off each other — and off your kids.
                        </p>
                        <a href="{{ route('parents') }}" class="btn btn-primary">Parents: Get Started</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">For Schools</h2>
                        <p class="text-muted">
                            This isn't a campaign asking schools for anything. Where a school wants to help — by
                            keeping class information current, or just by being visible on the site — a school user
                            can manage that themselves in a couple of minutes.
                        </p>
                        <a href="{{ route('schools.index') }}" class="btn btn-outline-primary">Schools: Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <h2 class="h2">How we make it actually work</h2>
            <p class="text-muted mb-0 mx-auto" style="max-width: 42rem;">
                Holding off works best when there's something real to look forward to, not just willpower.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Real discos and parties</h3>
                        <p class="small text-muted mb-0">
                            For classes that stick with it together — not just willpower, an actual celebration to
                            look forward to.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">A badge that grows</h3>
                        <p class="small text-muted mb-0">
                            Shown next to their own code name as your child's part of it grows — a fun way to
                            track progress, no real names needed.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Filling the gap</h3>
                        <p class="small text-muted mb-0">
                            With real stuff: reading together, playing together, celebrating together.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Never doing it alone</h3>
                        <p class="small text-muted mb-0">
                            That's the whole point.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <span class="badge text-bg-success mb-2">The other half of this</span>
            <h2 class="h2">It's not just about saying no</h2>
            <p class="text-muted mb-0 mx-auto" style="max-width: 42rem;">
                Delaying social media only works if there's something better to say yes to. Some of what we're
                already talking about doing together:
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Read it together</h3>
                        <p class="small text-muted mb-0">
                            Jonathan Haidt's <em>The Anxious Generation</em> is doing the rounds — a school or class
                            group reading it together, then getting together to talk it over, does more than any
                            leaflet could.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Celebrate finishing primary</h3>
                        <p class="small text-muted mb-0">
                            A proper end-of-year BBQ, disco or party for every family in a school who stuck with it
                            through 6th class — a real thank-you, not just a pledge on a page.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Meet before 1st year</h3>
                        <p class="small text-muted mb-0">
                            A get-together for the families carrying it into secondary school, so the kids walk in on
                            day one already knowing they're not the only ones without Snapchat.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6">Just play, no coach required</h3>
                        <p class="small text-muted mb-0">
                            Badminton, a kickaround, a bike spin — the point isn't training sessions, it's kids
                            meeting up, chatting, and messing about together. Longer term, we'd love to see proper
                            local youth clubs come out of this too.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('activities') }}" class="btn btn-outline-success">See Reclaim Free Time</a>
        </div>
    </section>

    <section class="py-5">
        <div class="card border-primary shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h2 class="h3 mb-2">Is your school missing? Add it here.</h2>
                        <p class="text-muted mb-0">
                            Parents can request that a school be added even if the school itself has not yet engaged.
                            If a school later chooses to support the initiative, it can be given secure access to
                            update its own class information and contact details.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('school-registration.create') }}" class="btn btn-primary btn-lg">Add My School</a>
                    </div>
                </div>
                <div class="small mt-3">Parent-led first. School support welcome, but not required.</div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <h2 class="h2">How it works</h2>
            <p class="text-muted mb-0">A simple local process, built around schools, classes and parent support.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary mb-3">1</div>
                        <h3 class="h5">Parents join at school level</h3>
                        <p class="text-muted mb-0">
                            Parents register under their child's school and class so support can be built in a practical,
                            local way.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary mb-3">2</div>
                        <h3 class="h5">Support builds from 4th class onward</h3>
                        <p class="text-muted mb-0">
                            The aim is to help parents start early, strengthen support through 4th, 5th and 6th class,
                            and carry that same group into 1st year at secondary school.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary mb-3">3</div>
                        <h3 class="h5">Schools may support, but parents lead</h3>
                        <p class="text-muted mb-0">
                            Some schools may help by confirming class sizes or contact information. Parent groups can
                            still move forward where a school does not participate formally.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h3 mb-3">This is a parent-led initiative</h2>
                        <p class="text-muted mb-0">
                            East Cork Reclaim Childhood is built by parents, for parents. It is designed to make it
                            easier for families to act together rather than alone. No school is under any obligation
                            to participate. No child or family is judged. The aim is simply to help parents connect,
                            reduce pressure, and support a healthier childhood.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h3 mb-3">Schools can support at their own pace</h2>
                        <p class="text-muted mb-0">
                            If a school chooses to support the initiative, it can nominate a secure school user who can
                            update class sizes and school contact details. This helps keep school information accurate
                            and reduces admin duplication. If a school prefers not to participate directly, the school
                            can still appear on the site and be managed by admin so parents can organise at local level.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="card border-0 bg-light">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h2 class="h4 mb-2">This is a local pilot. There's a national movement too.</h2>
                        <p class="text-muted mb-0">
                            <a href="https://smartphonefree.ie/" target="_blank" rel="noopener">Smartphone Free
                            Childhood Ireland</a> runs the national pledge and connects parents by WhatsApp group
                            right across the country — including secondary schools not far from us. We focus on
                            what they don't: real local schools, real classes, and real get-togethers here in East
                            Cork. Worth signing both.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="https://smartphonefree.ie/" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-lg">
                            See What's Happening Nationally
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <h2 class="h2">Common questions</h2>
            <p class="text-muted mb-0">Clear answers to the main issues parents and schools raise.</p>
        </div>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Is this anti-technology?
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No. A phone for calls and texts is fine by us at any age. It's social media specifically —
                        the apps designed to be addictive — that we're holding off on, not technology in general.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Why 16? That sounds like a long time.
                    </button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        16 is our stretch goal, not a rule — the honest aim is as late as reasonably possible, and
                        every year gained matters. Some families will land earlier than that, and that's still a win.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        What if my child already has a smartphone?
                    </button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Families are at different stages, and there's no judgment here. You can still join, still
                        hold off on social media specifically, and still help other families starting a step behind you.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        What is a "balance" phone?
                    </button>
                </h3>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        A simpler phone for calls, texts and maybe maps — built without an app store or social media.
                        It means your child is reachable and can reach you, without opening the door to everything else.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                        What if my school does not want to take part?
                    </button>
                </h3>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Parents can still organise at school level. School support is welcome, but not required.
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('faqs') }}" class="btn btn-outline-primary">Read All FAQs</a>
        </div>
    </section>

    <section class="py-5">
        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body p-4 p-lg-5 text-center">
                <h2 class="h2 mb-3">Stay informed as the network grows</h2>
                <p class="mb-4 text-white-50">
                    Get occasional updates as more schools, classes and local parent groups are added across East Cork.
                </p>
                <a href="{{ route('updates.create') }}" class="btn btn-warning btn-lg">Get Updates</a>
            </div>
        </div>
    </section>
@endsection
