@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Accounts</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    <p class="text-muted">
        Admin and school logins. They share a table with parents because the framework authenticates against one
        table, but they aren't parents and don't appear on that screen.
    </p>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Type</th><th>Name</th><th>Email</th><th>School</th><th>Created</th></tr>
            </thead>
            <tbody>
            @forelse ($accounts as $account)
                <tr>
                    <td>
                        <span class="badge {{ $account->user_type === 'admin' ? 'text-bg-danger' : 'text-bg-info' }}">
                            {{ ucfirst($account->user_type) }}
                        </span>
                    </td>
                    <td>{{ $account->name ?: $account->full_name }}</td>
                    <td>{{ $account->email }}</td>
                    <td>{{ optional($account->school)->name ?: '—' }}</td>
                    <td>{{ $account->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No admin or school accounts.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $accounts->links() }}
@endsection
