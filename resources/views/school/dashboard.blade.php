@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">School Dashboard</h1>
            <p class="text-muted mb-0">{{ $school->name }}</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Welcome</h2>

                    <p class="mb-3">
                        This area allows your school to update school details and manage class information.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('school.profile.edit') }}" class="btn btn-primary">
                            Edit School Details
                        </a>

                        <a href="{{ route('school.classes.index') }}" class="btn btn-outline-primary">
                            Manage Classes
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Current School Details</h2>

                    <dl class="row mb-0">
                        <dt class="col-sm-4">School Name</dt>
                        <dd class="col-sm-8">{{ $school->name }}</dd>

                        <dt class="col-sm-4">Slug</dt>
                        <dd class="col-sm-8">{{ $school->slug }}</dd>

                        <dt class="col-sm-4">School Type</dt>
                        <dd class="col-sm-8">{{ ucfirst($school->school_type) }}</dd>

                        <dt class="col-sm-4">Town</dt>
                        <dd class="col-sm-8">{{ $school->town ?: '—' }}</dd>

                        <dt class="col-sm-4">Website</dt>
                        <dd class="col-sm-8">
                            @if ($school->website_url)
                                <a href="{{ $school->website_url }}" target="_blank">{{ $school->website_url }}</a>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-4">School Telephone</dt>
                        <dd class="col-sm-8">{{ $school->school_phone ?: '—' }}</dd>

                        <dt class="col-sm-4">Principal</dt>
                        <dd class="col-sm-8">
                            @if ($school->principal_name)
                                <div>{{ $school->principal_name }}</div>
                            @endif
                            <div>{{ $school->principal_email ?: '—' }}</div>
                        </dd>

                        <dt class="col-sm-4">Vice Principal</dt>
                        <dd class="col-sm-8">
                            @if ($school->vice_principal_name)
                                <div>{{ $school->vice_principal_name }}</div>
                            @endif
                            <div>{{ $school->vice_principal_email ?: '—' }}</div>
                        </dd>

                        <dt class="col-sm-4">Secretary</dt>
                        <dd class="col-sm-8">
                            @if ($school->secretary_name)
                                <div>{{ $school->secretary_name }}</div>
                            @endif
                            <div>{{ $school->secretary_email ?: '—' }}</div>
                        </dd>

                        <dt class="col-sm-4">Support Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $school->support_status === 'supporting' ? 'text-bg-success' : 'text-bg-warning' }}">
                                {{ ucfirst($school->support_status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Record Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $school->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ ucfirst($school->status) }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Quick Links</h2>

                    <div class="d-grid gap-2">
                        <a href="{{ route('school.profile.edit') }}" class="btn btn-outline-secondary">
                            Edit School Details
                        </a>

                        <a href="{{ route('school.classes.index') }}" class="btn btn-outline-secondary">
                            Manage Classes
                        </a>

                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                            Return to Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
