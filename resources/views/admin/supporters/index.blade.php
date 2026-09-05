@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Supporters</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Search name or email"
                   value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.supporters.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Support Status</th>
                <th>Active</th>
                <th>Children</th>
                <th>Joined</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($people as $person)
                <tr>
                    <td>{{ $person->first_name }} {{ $person->last_name }}</td>
                    <td>{{ $person->email }}</td>
                    <td>{{ ucfirst($person->supporter->support_status) }}</td>
                    <td>{{ $person->supporter->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $person->children->count() }}</td>
                    <td>{{ optional($person->supporter->joined_at)->format('Y-m-d') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.supporters.show', $person) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $people->links() }}
@endsection
