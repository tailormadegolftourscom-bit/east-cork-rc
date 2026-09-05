@extends('layouts.app')

@php($pageTitle = 'About — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">About East Cork Reclaim Childhood</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            A parent-led initiative helping families in East Cork hold off on social media together, as late as
            16 where we can manage it — and give kids more of a real, outdoors, screen-free childhood in the meantime.
        </p>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">A pilot, with bigger ambitions</h2>
                        <p class="text-muted mb-0">
                            East Cork is where we're starting. We're building this deliberately so that, if it works
                            well here, the same approach can be used by parent groups in other parts of Ireland —
                            each with their own local schools, committees and supporters, alongside shared national
                            content on the wider issue.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Parent-led, always</h2>
                        <p class="text-muted mb-0">
                            This initiative is run by parents, for parents. Schools are welcome to support it — and
                            many of the practical benefits, like accurate class information, depend on schools choosing
                            to help — but no school is ever required to take part, and parents can organise regardless.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-light mb-5">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h5 mb-3">How it's organised</h2>
                <p class="text-muted mb-0">
                    East Cork Reclaim Childhood is the regional committee for this area. Each participating school has
                    its own school committee, which feeds into the regional one — a structure designed to scale
                    naturally as more schools and, eventually, more areas get involved.
                </p>
            </div>
        </div>

        <div class="text-center">
            <p class="text-muted mb-2">Questions, ideas, or want to help organise locally?</p>
            <a href="mailto:info@eastcorkreclaimchildhood.ie" class="btn btn-outline-primary">Get in Touch</a>
        </div>
    </section>
@endsection
