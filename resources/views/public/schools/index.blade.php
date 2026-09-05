@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="mb-4">
            <h1 class="display-6 fw-bold mb-3">Schools in East Cork</h1>
            <p class="lead text-muted mb-3">
                These are the schools currently active in the East Cork Reclaim Childhood network.
            </p>
            <p class="text-muted mb-0">
                This is a parent-led initiative. A school may appear here even if its own support status is still undecided.
                Where a school has chosen to support the initiative, that is shown below.
            </p>
        </div>

        @php
            $primarySchools = $schools->get('primary', collect());
            $secondarySchools = $schools->get('secondary', collect());
        @endphp

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-primary">{{ $primarySchools->count() }}</div>
                        <div class="text-muted">Active primary schools</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-primary">{{ $secondarySchools->count() }}</div>
                        <div class="text-muted">Active secondary schools</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h2 class="h3 mb-3">Primary Schools</h2>

            @if ($primarySchools->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="mb-0 text-muted">No active primary schools are listed yet.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($primarySchools as $school)
                        <div class="col-lg-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h3 class="h5 mb-0">{{ $school->name }}</h3>

                                        <span class="badge {{ $school->support_status === 'supporting' ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ $school->support_status === 'supporting' ? 'School Supporting' : 'School Undecided' }}
                                        </span>
                                    </div>

                                    <p class="text-muted mb-2">
                                        {{ $school->town ?: 'East Cork' }}
                                    </p>

                                    @if ($school->school_phone)
                                        <p class="mb-1 small text-muted">Tel: {{ $school->school_phone }}</p>
                                    @endif

                                    @if ($school->website_url)
                                        <p class="mb-0">
                                            <a href="{{ $school->website_url }}" target="_blank" class="text-decoration-none">
                                                School website
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h2 class="h3 mb-3">Secondary Schools</h2>

            @if ($secondarySchools->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="mb-0 text-muted">No active secondary schools are listed yet.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($secondarySchools as $school)
                        <div class="col-lg-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h3 class="h5 mb-0">{{ $school->name }}</h3>

                                        <span class="badge {{ $school->support_status === 'supporting' ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ $school->support_status === 'supporting' ? 'School Supporting' : 'School Undecided' }}
                                        </span>
                                    </div>

                                    <p class="text-muted mb-2">
                                        {{ $school->town ?: 'East Cork' }}
                                    </p>

                                    @if ($school->school_phone)
                                        <p class="mb-1 small text-muted">Tel: {{ $school->school_phone }}</p>
                                    @endif

                                    @if ($school->website_url)
                                        <p class="mb-0">
                                            <a href="{{ $school->website_url }}" target="_blank" class="text-decoration-none">
                                                School website
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
