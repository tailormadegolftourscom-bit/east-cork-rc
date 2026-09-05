@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Committees</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    @forelse ($regional as $committee)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h5 mb-1">{{ $committee->name }}</h2>
                <p class="text-muted mb-3">
                    Regional committee &middot; slug: <code>{{ $committee->slug }}</code>
                    &middot; status: {{ ucfirst($committee->status) }}
                    @if ($committee->area)
                        &middot; area: {{ $committee->area->name }}
                    @endif
                </p>

                @php($children = $bySchool->get($committee->id, collect()))

                @if ($children->isNotEmpty())
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th>School Committee</th>
                            <th>School</th>
                            <th>Town</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($children as $child)
                            <tr>
                                <td>{{ $child->name }}</td>
                                <td>
                                    @if ($child->school)
                                        <a href="{{ route('admin.schools.edit', $child->school) }}">{{ $child->school->name }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $child->town ?: '—' }}</td>
                                <td>{{ ucfirst($child->status) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">No school committees under this regional committee yet.</p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">No committees found.</p>
    @endforelse
@endsection
