@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Workshop RSVPs</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <p class="text-muted">
        {{ config('notice.venue') }}. Numbers are how many people each person is bringing.
    </p>

    @forelse ($workshops as $workshop)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h5 mb-0">{{ $workshop['label'] }}</h2>
                    <div>
                        <span class="badge text-bg-primary">
                            {{ $workshop['rsvps']->count() }} {{ Str::plural('RSVP', $workshop['rsvps']->count()) }}
                        </span>
                        <span class="badge text-bg-secondary">
                            {{ $workshop['attendees'] }} {{ Str::plural('person', $workshop['attendees']) }}
                        </span>
                    </div>
                </div>

                @if ($workshop['rsvps']->isEmpty())
                    <p class="text-muted mb-0">Nobody yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Name</th><th>Email</th><th>Phone</th><th>Coming</th>
                                <th>Note</th><th>RSVP'd</th><th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($workshop['rsvps'] as $rsvp)
                                <tr>
                                    <td>{{ $rsvp->name }}</td>
                                    <td>{{ $rsvp->email }}</td>
                                    <td>{{ $rsvp->phone ?: '—' }}</td>
                                    <td>{{ $rsvp->attendees }}</td>
                                    <td class="small">{{ $rsvp->note ?: '—' }}</td>
                                    <td class="small text-muted">{{ $rsvp->created_at->format('j M') }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="collapse" data-bs-target="#del-rsvp-{{ $rsvp->id }}">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="del-rsvp-{{ $rsvp->id }}">
                                    <td colspan="7" class="bg-light">
                                        <form method="POST" action="{{ route('admin.workshops.destroy', $rsvp) }}"
                                              class="row g-2 align-items-end">
                                            @csrf @method('DELETE')
                                            <div class="col">
                                                <label class="form-label small">
                                                    Reason for removing {{ $rsvp->name }}
                                                </label>
                                                <input type="text" name="reason" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-sm btn-danger">Confirm</button>
                                            </div>
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
    @empty
        <div class="alert alert-secondary">No workshops are configured.</div>
    @endforelse

    @if ($alternatives->isNotEmpty())
        <div class="card shadow-sm mb-4 border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h5 mb-0">Neither evening suits</h2>
                    <span class="badge text-bg-warning">
                        {{ $alternatives->count() }} {{ Str::plural('person', $alternatives->count()) }}
                    </span>
                </div>

                <p class="text-muted small">
                    These people asked for another evening and said when would work. Enough of them and it is
                    worth arranging a third.
                </p>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Coming</th><th>When would suit</th><th></th></tr>
                        </thead>
                        <tbody>
                        @foreach ($alternatives as $rsvp)
                            <tr>
                                <td>{{ $rsvp->name }}</td>
                                <td>{{ $rsvp->email }}</td>
                                <td>{{ $rsvp->phone ?: '—' }}</td>
                                <td>{{ $rsvp->attendees }}</td>
                                <td class="small">{{ $rsvp->note }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="collapse" data-bs-target="#del-alt-{{ $rsvp->id }}">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="del-alt-{{ $rsvp->id }}">
                                <td colspan="6" class="bg-light">
                                    <form method="POST" action="{{ route('admin.workshops.destroy', $rsvp) }}"
                                          class="row g-2 align-items-end">
                                        @csrf @method('DELETE')
                                        <div class="col">
                                            <label class="form-label small">Reason for removing {{ $rsvp->name }}</label>
                                            <input type="text" name="reason" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-sm btn-danger">Confirm</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
