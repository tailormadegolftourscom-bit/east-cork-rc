@extends('layouts.app')

@php($pageTitle = 'Resources — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">Resources</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            Practical answers to the questions parents actually ask us, and a couple of alternatives worth knowing
            about before you hand over a full smartphone.
        </p>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Talking to your child</h2>
                        <p class="text-muted mb-0">
                            "Because I said so" rarely lands well, especially once other kids in the class have a
                            phone. It helps to be honest about why: that these apps are built by very smart people
                            specifically to be hard to put down, that you're not doing this to punish them, and that
                            you're doing it alongside other parents — not singling them out.
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
                        <h2 class="h5 mb-3">Alternatives to a full smartphone</h2>
                        <p class="text-muted mb-0">
                            If your child needs a way to contact you or be contacted for safety reasons, a simple
                            "balance" or "brick" phone — calls and texts only, no app store, no social media — covers
                            that without opening the door to everything else.
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

        <div class="card border-0 bg-light">
            <div class="card-body p-4 p-lg-5 text-center">
                <h2 class="h5 mb-2">More on the way</h2>
                <p class="text-muted mb-0">
                    As the network grows we plan to add printable materials, guidance for hosting a parent discussion,
                    and stories from families and schools already doing this. See
                    <a href="{{ route('faqs') }}">FAQs</a> for quick answers in the meantime.
                </p>
            </div>
        </div>
    </section>
@endsection
