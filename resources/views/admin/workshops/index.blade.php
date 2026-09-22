@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Workshop RSVPs</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <p class="text-muted">
        {{ config('notice.venue') }}. Numbers include anyone the person is bringing.
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
                            {{ $workshop['adults'] }} {{ Str::plural('adult', $workshop['adults']) }}
                        </span>
                        @if ($workshop['children'])
                            <span class="badge text-bg-secondary">
                                {{ $workshop['children'] }} {{ Str::plural('child', $workshop['children']) }}
                            </span>
                        @endif
                    </div>
                </div>

                @if ($workshop['rsvps']->isEmpty())
                    <p class="text-muted mb-0">Nobody yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Name</th><th>Email</th><th>Phone</th><th>Party</th>
                                <th>Note</th><th>RSVP'd</th><th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($workshop['rsvps'] as $rsvp)
                                <tr>
                                    <td>{{ $rsvp->name }}</td>
                                    <td>{{ $rsvp->email }}</td>
                                    <td>{{ $rsvp->phone ?: '—' }}</td>
                                    <td>
                                        {{ $rsvp->adults }}
                                        @if ($rsvp->children)
                                            + {{ $rsvp->children }} {{ Str::plural('child', $rsvp->children) }}
                                        @endif
                                    </td>
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
@endsection
