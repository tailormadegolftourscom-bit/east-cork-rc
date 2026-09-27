@extends('layouts.app')

@php($pageTitle = 'Resources — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">Resources</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            Places in East Cork where children can get out and do things together, and where parents can find
            help elsewhere.
        </p>

        {{-- Local resources: the venues table, so admin keeps it current. --}}
        <div class="mb-5">
            <h2 class="h4 mb-3">Local resources</h2>
            <p class="text-muted mb-4" style="max-width: 46rem;">
                Halls, centres and routes around East Cork for getting children out, active and together.
                For what's happening at them, see <a href="{{ route('activities') }}">What's On</a>.
            </p>

            @if ($venues->isEmpty())
                <p class="text-muted">None listed yet.</p>
            @else
                <div class="row g-4">
                    @foreach ($venues as $venue)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="h5 mb-1">{{ $venue->name }}</h3>
                                    @if ($venue->town && ! str_contains($venue->name, $venue->town))
                                        <p class="small text-muted mb-2">{{ $venue->town }}</p>
                                    @endif
                                    @if ($venue->description)
                                        <p class="text-muted mb-3">{{ $venue->description }}</p>
                                    @endif
                                    <div class="d-flex flex-wrap gap-3 small">
                                        @if ($venue->website_url)
                                            <a href="{{ $venue->website_url }}" target="_blank" rel="noopener">Website</a>
                                        @endif
                                        @if ($venue->map_url)
                                            <a href="{{ $venue->map_url }}" target="_blank" rel="noopener">Map</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="small text-muted mt-3 mb-0">
                    Know somewhere that should be here?
                    <a href="mailto:info@eastcorkreclaimchildhood.ie?subject=Venue%20suggestion">Suggest a venue</a>.
                </p>
            @endif
        </div>

        {{-- Help for parents: other organisations, linked rather than copied. --}}
        <div class="mb-5">
            <h2 class="h4 mb-3">Help for parents</h2>
            <p class="text-muted mb-4" style="max-width: 46rem;">
                Others have already written excellent guides. Rather than repeat them, here's where to find them.
            </p>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-1">Smartphone Free Childhood Ireland</h3>
                            <p class="small text-muted mb-3">The national movement in Ireland.</p>
                            <ul class="mb-3">
                                <li><a href="https://smartphonefree.ie/start-a-delay-group/" target="_blank" rel="noopener">Start a Delay Group</a> &mdash; setting up a WhatsApp group for your child's class</li>
                                <li><a href="https://smartphonefree.ie/delay-for-secondary/" target="_blank" rel="noopener">Delay for Secondary</a> &mdash; carrying it into secondary school</li>
                            </ul>
                            <a href="https://smartphonefree.ie/" target="_blank" rel="noopener" class="small">smartphonefree.ie &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-1">Smartphone Free Childhood (UK)</h3>
                            <p class="small text-muted mb-3">
                                The UK movement that started it all, with a large library of guides for parents.
                            </p>
                            <ul class="mb-3">
                                <li><a href="https://www.smartphonefreechildhood.org/resource/how-should-i-talk-to-my-child-about-smartphones" target="_blank" rel="noopener">How to talk to your child about smartphones</a></li>
                                <li><a href="https://www.smartphonefreechildhood.org/resource/how-to-talk-other-parents-about-delaying-smartphones-without-sounding-judgemental" target="_blank" rel="noopener">Talking to other parents without sounding judgemental</a></li>
                                <li><a href="https://www.smartphonefreechildhood.org/resource/how-to-navigate-sleepovers-and-playdates" target="_blank" rel="noopener">Sleepovers and playdates with kids who have smartphones</a></li>
                                <li><a href="https://www.smartphonefreechildhood.org/alternatives" target="_blank" rel="noopener">Find a phone alternative</a> &mdash; a 60-second quiz</li>
                            </ul>
                            <a href="https://www.smartphonefreechildhood.org/resources-for-parents" target="_blank" rel="noopener" class="small">All their resources for parents &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-1">CyberSafeKids</h3>
                            <p class="small text-muted mb-3">Irish online safety charity for children and families.</p>
                            <p class="mb-3">
                                Runs <a href="https://www.cybersafekids.ie/cyberbreak/" target="_blank" rel="noopener">CyberBreak</a>,
                                a 24-hour national switch-off on 16&ndash;17 October. Our Greenway cycle rounds off
                                that weekend on Sunday 18 October at 1pm.
                            </p>
                            <a href="https://www.cybersafekids.ie/" target="_blank" rel="noopener" class="small">cybersafekids.ie &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-warning">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-1">A book for your child: <em>The Amazing Generation</em></h3>
                            <p class="small text-muted mb-3">By Jonathan Haidt and Catherine Price, for 9 to 13-year-olds.</p>
                            <p class="mb-0">
                                The follow-on from <em>The Anxious Generation</em>, and where our Gen Alpha Rebels and
                                Greedy Wizards come from. It puts kids on the side of taking back their own time,
                                rather than being told no &mdash; a good read-together for a class or family.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light">
            <div class="card-body p-4 p-lg-5 text-center">
                <h2 class="h5 mb-2">Know a useful resource?</h2>
                <p class="text-muted mb-0">
                    A local club, a hall, a good guide for parents &mdash;
                    <a href="mailto:info@eastcorkreclaimchildhood.ie?subject=Resource%20suggestion">send it in</a>
                    and it can be added here. Quick answers are in the <a href="{{ route('faqs') }}">FAQs</a>.
                </p>
            </div>
        </div>
    </section>
@endsection
