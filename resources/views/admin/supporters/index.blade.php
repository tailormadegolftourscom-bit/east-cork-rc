@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Supporters</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <p class="text-muted">
        People backing the initiative who aren't parents here.
        <a href="{{ route('admin.parents.index') }}">Parents</a> are supporters by definition and listed separately.
    </p>

    <form method="GET" class="row g-2 align-items-center mb-3">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Search name or email"
                   value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto form-check">
            <input type="checkbox" class="form-check-input" id="inactive" name="inactive" value="1"
                   @checked(request()->boolean('inactive')) onchange="this.form.submit()">
            <label class="form-check-label" for="inactive">Show inactive ({{ $inactiveCount }})</label>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.supporters.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Name</th><th>Email</th><th>Offering</th><th>Contact</th><th>Joined</th><th></th></tr>
            </thead>
            <tbody>
            @forelse ($supporters as $supporter)
                <tr class="{{ $supporter->is_active ? '' : 'table-secondary' }}">
                    <td>
                        {{ $supporter->full_name }}
                        @unless ($supporter->is_active)
                            <span class="badge text-bg-secondary">Inactive</span>
                        @endunless
                    </td>
                    <td>{{ $supporter->email }}</td>
                    <td>
                        @forelse ($supporter->categories as $category)
                            <span class="badge text-bg-light border">{{ $category->name }}</span>
                        @empty
                            <span class="text-muted">&mdash;</span>
                        @endforelse
                    </td>
                    <td>{{ ucfirst($supporter->preferred_contact_method) }}</td>
                    <td>{{ optional($supporter->joined_at)->format('Y-m-d') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.supporters.show', $supporter) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">No supporters yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $supporters->links() }}
@endsection
