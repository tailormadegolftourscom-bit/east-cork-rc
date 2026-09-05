@extends('layouts.app')

@php($pageTitle = 'Resources — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">Resources</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            Practical answers to the questions parents actually ask us, and a couple of alternatives worth knowing
            about while social media waits.
        </p>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Talking to your child</h2>
                        <p class="text-muted mb-0">
                            "Because I said so" rarely lands well, especially once other kids in the class have
                            Snapchat or TikTok. It helps to be honest about why: that these apps are built by very
                            smart people specifically to be hard to put down, that you're not doing this to punish
                            them, and that you're doing it alongside other parents — not singling them out.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Talking to other parents</h2>
                        <p class="text-muted mb-0">
                            Most parents are relieved when someone else raises this first — a lot of families give in
                            because they assume everyone else already has, not because they think it's the right age.
                            A short, friendly message to your child's class WhatsApp group is often all it takes to
                            find out you're not alone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Balance phones</h2>
                        <p class="text-muted mb-0">
                            A simple "balance" or "brick" phone — calls and texts, maybe maps, no app store, no social
                            media — means your child is reachable without opening the door to everything else. For a
                            lot of us, this isn't a stopgap, it's the actual plan right up to 16.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">If your child already has one</h2>
                        <p class="text-muted mb-0">
                            Every family starts from a different place, and there's no judgment here. You can still
                            join, still support other parents starting earlier with younger children, and still tighten
                            things up gradually — it doesn't have to be all or nothing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-4">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h5 mb-3">Already covered well nationally</h2>
                <p class="text-muted mb-3">
                    Rather than write our own version, here's where to go for things
                    <a href="https://smartphonefree.ie/" target="_blank" rel="noopener">Smartphone Free Childhood
                    Ireland</a> already does well:
                </p>
                <ul class="text-muted mb-0">
                    <li>Setting up and running a WhatsApp delay group — their
                        <a href="https://smartphonefree.ie/start-a-delay-group/" target="_blank" rel="noopener">Start
                        a Delay Group</a> guide</li>
                    <li>Carrying it into secondary school — their
                        <a href="https://smartphonefree.ie/delay-for-secondary/" target="_blank" rel="noopener">Delay
                        for Secondary</a> guide</li>
                    <li>The wider evidence and risk picture — their
                        <a href="https://smartphonefree.ie/" target="_blank" rel="noopener">national site</a></li>
                </ul>
            </div>
        </div>

        <div class="card border-0 bg-light">
            <div class="card-body p-4 p-lg-5 text-center">
                <h2 class="h5 mb-2">More on the way, here in East Cork</h2>
                <p class="text-muted mb-0">
                    What we'll keep building ourselves: help organising local get-togethers and celebrations, and
                    stories from East Cork families and schools already doing this. See
                    <a href="{{ route('faqs') }}">FAQs</a> for quick answers in the meantime.
                </p>
            </div>
        </div>
    </section>
@endsection
