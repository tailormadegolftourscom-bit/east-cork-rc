@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Back to Users</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6">Account</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Role</dt>
                        <dd class="col-sm-8">{{ ucfirst($user->user_type) }}{{ $user->is_admin ? ' (is_admin flag set)' : '' }}</dd>

                        <dt class="col-sm-4">Email Verified</dt>
                        <dd class="col-sm-8">{{ $user->email_verified_at?->format('Y-m-d H:i') ?: 'Not verified' }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            @if ($user->suspended_at)
                                <span class="badge text-bg-warning">Suspended since {{ $user->suspended_at->format('Y-m-d') }}</span>
                            @else
                                <span class="badge text-bg-success">Active</span>
                            @endif
                        </dd>

                        @if ($user->school)
                            <dt class="col-sm-4">School</dt>
                            <dd class="col-sm-8">
                                <a href="{{ route('admin.schools.edit', $user->school) }}">{{ $user->school->name }}</a>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if ($user->person)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h6">Linked Person (real identity)</h2>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Name</dt>
                            <dd class="col-sm-8">{{ $user->person->first_name }} {{ $user->person->last_name }}</dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ $user->person->email }}</dd>

                            <dt class="col-sm-4">Phone</dt>
                            <dd class="col-sm-8">{{ $user->person->phone ?: '—' }}</dd>

                            <dt class="col-sm-4">Public Name Mode</dt>
                            <dd class="col-sm-8">{{ $user->person->public_name_mode === 'anon_code' ? 'Anonymous (' . $user->person->public_display_name . ')' : 'Real name' }}</dd>

                            <dt class="col-sm-4">Children</dt>
                            <dd class="col-sm-8">
                                @if ($user->person->children->isEmpty())
                                    None registered
                                @else
                                    {{ $user->person->children->pluck('first_name')->join(', ') }} —
                                    <a href="{{ route('admin.supporters.show', $user->person) }}">view details</a>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            @endif

            @if ($auditLog->isNotEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h6">Audit History</h2>
                        <ul class="list-unstyled mb-0 small">
                            @foreach ($auditLog as $entry)
                                <li class="mb-2">
                                    <strong>{{ $entry->action }}</strong>
                                    by {{ optional($entry->actor)->name ?: 'system' }}
                                    on {{ $entry->created_at->format('Y-m-d H:i') }}
                                    @if ($entry->reason)
                                        &mdash; {{ $entry->reason }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 mb-3">Account Actions</h2>

                    @if ($user->id === auth()->id())
                        <p class="text-muted mb-0">You cannot suspend or delete your own account.</p>
                    @else
                        @if ($user->suspended_at)
                            <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">Reactivate Account</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="mb-3">
                                @csrf
                                <label for="suspend_reason" class="form-label">Reason for suspension</label>
                                <textarea class="form-control mb-2" id="suspend_reason" name="reason" rows="2" required></textarea>
                                <button type="submit" class="btn btn-warning w-100">Suspend Account</button>
                            </form>
                        @endif

                        <hr>

                        <h3 class="h6 text-danger">Delete Account</h3>
                        <p class="small text-muted">
                            This permanently deletes the login account. It does <strong>not</strong> delete
                            the linked person, supporter, or child records &mdash; review those separately first.
                        </p>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <label for="delete_reason" class="form-label">Reason for deletion</label>
                            <textarea class="form-control mb-2" id="delete_reason" name="reason" rows="2" required></textarea>

                            <label for="confirm_email" class="form-label">
                                Type <code>{{ $user->email }}</code> to confirm
                            </label>
                            <input type="text" class="form-control mb-2" id="confirm_email" name="confirm_email" required>

                            <button type="submit" class="btn btn-outline-danger w-100">Permanently Delete User</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
