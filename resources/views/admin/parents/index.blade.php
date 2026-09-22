@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Parents</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <p class="text-muted">
        Every parent here is a supporter — registering is the support.
        <a href="{{ route('admin.supporters.index') }}">Supporters who aren't parents</a> are listed separately.
    </p>

    <form method="GET" class="row g-2 align-items-center mb-3">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Search name or email"
                   value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <select name="state" class="form-select" onchange="this.form.submit()">
                <option value="">All ({{ $counts['all'] }})</option>
                <option value="registered" @selected(request('state') === 'registered')>
                    Registered ({{ $counts['registered'] }})
                </option>
                <option value="pending" @selected(request('state') === 'pending')>
                    Not finished ({{ $counts['pending'] }})
                </option>
                <option value="no_children" @selected(request('state') === 'no_children')>
                    Registered, no child yet
                </option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.parents.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Children</th>
                <th>Also offers</th>
                <th>Joined</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($parents as $parent)
                <tr class="{{ $parent->hasOnboarded() && $parent->children->isEmpty() ? 'table-warning' : '' }}">
                    <td>
                        {{ $parent->full_name }}
                        @if ($parent->suspended_at)
                            <span class="badge text-bg-dark">Suspended</span>
                        @endif
                    </td>
                    <td>{{ $parent->email }}</td>
                    <td>
                        @if ($parent->hasOnboarded())
                            <span class="badge text-bg-success">Parent &amp; Supporter</span>
                        @else
                            <span class="badge text-bg-secondary">Not finished</span>
                            @if (! $parent->hasCompletedRegistration())
                                <span class="d-block text-muted small">No password set</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        {{ $parent->children->count() }}
                        @if ($parent->hasOnboarded() && $parent->children->isEmpty())
                            <span class="badge text-bg-warning">No child yet</span>
                        @endif
                    </td>
                    <td>
                        @forelse ($parent->categories as $category)
                            <span class="badge text-bg-light border">{{ $category->name }}</span>
                        @empty
                            <span class="text-muted">&mdash;</span>
                        @endforelse
                    </td>
                    <td>{{ optional($parent->onboarded_at ?? $parent->created_at)->format('Y-m-d') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.parents.show', $parent) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-muted">No parents match that filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $parents->links() }}
@endsection
