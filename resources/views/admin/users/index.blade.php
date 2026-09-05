@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Users</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Search name or email"
                   value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <select name="user_type" class="form-select">
                <option value="">All roles</option>
                <option value="admin" @selected(request('user_type') === 'admin')>Admin</option>
                <option value="school" @selected(request('user_type') === 'school')>School</option>
                <option value="parent" @selected(request('user_type') === 'parent')>Parent</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Verified</th>
                <th>School</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr class="{{ $user->suspended_at ? 'table-warning' : '' }}">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge text-bg-secondary">{{ ucfirst($user->user_type) }}</span></td>
                    <td>
                        @if ($user->email_verified_at)
                            <span class="text-success">Verified</span>
                        @else
                            <span class="text-muted">Unverified</span>
                        @endif
                    </td>
                    <td>{{ optional($user->school)->name ?: '—' }}</td>
                    <td>
                        @if ($user->suspended_at)
                            <span class="badge text-bg-warning">Suspended</span>
                        @else
                            <span class="badge text-bg-success">Active</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endsection
