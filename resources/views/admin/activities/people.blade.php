@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-0">{{ $activity->title }}</h1>
            <p class="text-muted mb-0">{{ $activity->whenLabel() }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activities.show', $activity) }}" class="btn btn-outline-primary btn-sm">Public Page</a>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Signed up</h2>
                <span class="badge text-bg-success">{{ $signups->count() }} {{ Str::plural('Rebel', $signups->count()) }}</span>
            </div>

            @if ($signups->isEmpty())
                <p class="text-muted mb-0">Nobody yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr><th>Child</th><th>School &amp; class</th><th>Parent</th><th>Email</th><th>Phone</th><th>Signed up</th></tr>
                        </thead>
                        <tbody>
                        @foreach ($signups as $signup)
                            @php($child = $signup->child)
                            @php($contact = $signup->parent ?? $child?->owner)
                            <tr>
                                <td>
                                    {{ trim(($child->first_name ?? '').' '.($child->last_name ?? '')) }}
                                    <span class="d-block small text-muted">{{ $child->public_label ?? '' }}</span>
                                </td>
                                <td class="small">
                                    {{ $child?->schoolLink?->currentSchool?->name ?? '—' }}
                                    @if ($child?->schoolLink?->currentSchoolClass)
                                        <span class="d-block text-muted">{{ $child->schoolLink->currentSchoolClass->display_name }}</span>
                                    @endif
                                </td>
                                <td>{{ $contact?->full_name ?? '—' }}</td>
                                <td class="small">{{ $contact?->email ?? '—' }}</td>
                                <td class="small">{{ $contact?->phone ?: '—' }}</td>
                                <td class="small text-muted text-nowrap">{{ $signup->created_at->format('j M') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Volunteers</h2>
                <span class="badge text-bg-warning">{{ $volunteers->count() }}</span>
            </div>

            @if ($volunteers->isEmpty())
                <p class="text-muted mb-0">Nobody yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Note</th><th></th><th>Offered</th></tr>
                        </thead>
                        <tbody>
                        @foreach ($volunteers as $volunteer)
                            <tr>
                                <td>{{ $volunteer->name }}</td>
                                <td class="small">{{ $volunteer->email }}</td>
                                <td class="small">{{ $volunteer->phone ?: '—' }}</td>
                                <td class="small">{{ $volunteer->note ?: '—' }}</td>
                                <td class="small text-muted">{{ $volunteer->parent_id ? 'Parent' : 'Supporter' }}</td>
                                <td class="small text-muted text-nowrap">{{ $volunteer->created_at->format('j M') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
