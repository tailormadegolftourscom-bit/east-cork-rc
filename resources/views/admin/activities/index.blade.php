@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h1 class="h3 mb-0">Activities &amp; Venues</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.activities.create') }}" class="btn btn-primary btn-sm">Add Activity</a>
            <a href="{{ route('admin.venues.create') }}" class="btn btn-outline-primary btn-sm">Add Venue</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
        </div>
    </div>

    <p class="text-muted">
        Everything active shows on <a href="{{ route('activities') }}">Reclaim Free Time</a>, Resources and the
        homepage. A dated activity drops off the public list the day after it happens.
    </p>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Activities</h2>

            @if ($activities->isEmpty())
                <p class="text-muted mb-0">None yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr><th>Activity</th><th>Status</th><th>When</th><th>Venue</th><th>Convenor</th><th>Shown</th><th></th></tr>
                        </thead>
                        <tbody>
                        @foreach ($activities as $activity)
                            <tr class="{{ $activity->isPast() || ! $activity->is_active ? 'text-muted' : '' }}">
                                <td class="fw-semibold">{{ $activity->title }}</td>
                                <td>{{ $activity->statusLabel() }}</td>
                                <td class="small">{{ $activity->whenLabel() ?: '—' }}</td>
                                <td class="small">{{ $activity->venue?->label ?? '—' }}</td>
                                <td class="small">{{ $activity->convenor_name ?: '—' }}</td>
                                <td class="small">
                                    @if (! $activity->is_active) Hidden
                                    @elseif ($activity->isPast()) Past
                                    @else Yes
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" class="d-inline"
                                          onsubmit="return confirm('Delete {{ addslashes($activity->title) }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @php($pendingCount = $suggestions->where('status', 'pending')->count())
    <div class="card shadow-sm mb-4 {{ $pendingCount ? 'border-warning' : '' }}" id="suggestions">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h2 class="h5 mb-0">Suggestions</h2>
                @if ($pendingCount)
                    <span class="badge text-bg-warning">{{ $pendingCount }} waiting for approval</span>
                @endif
            </div>
            <p class="text-muted small">
                Parents' suggestions only appear on the public page once approved. Names are never shown publicly.
            </p>

            @if ($suggestions->isEmpty())
                <p class="text-muted mb-0">None yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr><th>Suggestion</th><th>Activity</th><th>From</th><th>Sent</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                        @foreach ($suggestions as $suggestion)
                            <tr class="{{ $suggestion->status === 'hidden' ? 'text-muted' : '' }}">
                                <td>{{ $suggestion->body }}</td>
                                <td class="small">
                                    @if ($suggestion->activity)
                                        <a href="{{ route('activities.suggestions', $suggestion->activity) }}">{{ $suggestion->activity->title }}</a>
                                    @endif
                                </td>
                                <td class="small">{{ $suggestion->parent?->full_name ?? 'ECRC' }}</td>
                                <td class="small text-muted text-nowrap">{{ $suggestion->created_at->format('j M') }}</td>
                                <td class="small">{{ ucfirst($suggestion->status) }}</td>
                                <td class="text-end text-nowrap">
                                    @foreach (['approved' => ['Approve', 'btn-outline-success'], 'hidden' => ['Hide', 'btn-outline-secondary']] as $status => [$label, $class])
                                        @if ($suggestion->status !== $status)
                                            <form method="POST" action="{{ route('admin.activity-suggestions.moderate', $suggestion) }}" class="d-inline">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $status }}">
                                                <button type="submit" class="btn btn-sm {{ $class }}">{{ $label }}</button>
                                            </form>
                                        @endif
                                    @endforeach
                                </td>
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
            <h2 class="h5 mb-3">Venues</h2>

            @if ($venues->isEmpty())
                <p class="text-muted mb-0">None yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr><th>Venue</th><th>Town</th><th>Activities</th><th>Shown</th><th></th></tr>
                        </thead>
                        <tbody>
                        @foreach ($venues as $venue)
                            <tr class="{{ $venue->is_active ? '' : 'text-muted' }}">
                                <td class="fw-semibold">{{ $venue->name }}</td>
                                <td>{{ $venue->town ?: '—' }}</td>
                                <td>{{ $venue->activities_count }}</td>
                                <td class="small">{{ $venue->is_active ? 'Yes' : 'Hidden' }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.venues.destroy', $venue) }}" class="d-inline"
                                          onsubmit="return confirm('Delete {{ addslashes($venue->name) }}? Its activities stay, without a venue.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
