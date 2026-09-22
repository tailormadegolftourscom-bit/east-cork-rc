@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $parent->full_name }}</h1>
        <a href="{{ route('admin.parents.index') }}" class="btn btn-outline-secondary btn-sm">Back to Parents</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6">Contact &amp; Preferences</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $parent->email }}</dd>

                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $parent->phone ?: '—' }}</dd>

                        <dt class="col-sm-4">Preferred Contact</dt>
                        <dd class="col-sm-8">{{ ucfirst($parent->preferred_contact_method) }}</dd>

                        <dt class="col-sm-4">Shown Publicly As</dt>
                        <dd class="col-sm-8">{{ $parent->public_display_name }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            @if ($parent->hasOnboarded())
                                <span class="badge text-bg-success">Parent &amp; Supporter</span>
                            @else
                                <span class="badge text-bg-secondary">Registration not finished</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Password set</dt>
                        <dd class="col-sm-8">
                            {{ $parent->hasCompletedRegistration()
                                ? $parent->registration_completed_at->format('Y-m-d')
                                : 'Never — cannot log in' }}
                        </dd>

                        <dt class="col-sm-4">Email verified</dt>
                        <dd class="col-sm-8">
                            {{ $parent->email_verified_at ? $parent->email_verified_at->format('Y-m-d') : 'No' }}
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 mb-3">Children</h2>

                    @if ($parent->children->isEmpty())
                        <p class="text-muted mb-0">No children registered.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                <tr><th>Name</th><th>School</th><th>Class</th><th>Audit</th><th></th></tr>
                                </thead>
                                <tbody>
                                @foreach ($parent->children as $child)
                                    <tr>
                                        <td>{{ $child->first_name }} {{ $child->last_name }}</td>
                                        <td>{{ optional($child->schoolLink?->currentSchool)->name ?: 'Not set' }}</td>
                                        <td>{{ optional($child->schoolLink?->currentSchoolClass)->display_name ?: '—' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.children.update-audit-status', $child) }}">
                                                @csrf @method('PATCH')
                                                <select name="audit_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" @selected($child->audit_status === 'pending')>Pending</option>
                                                    <option value="reviewed" @selected($child->audit_status === 'reviewed')>Reviewed</option>
                                                    <option value="verified" @selected($child->audit_status === 'verified')>Verified</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="collapse" data-bs-target="#del-child-{{ $child->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="del-child-{{ $child->id }}">
                                        <td colspan="5" class="bg-light">
                                            <form method="POST" action="{{ route('admin.children.destroy', $child) }}"
                                                  class="row g-2 align-items-end">
                                                @csrf @method('DELETE')
                                                <div class="col">
                                                    <label class="form-label small">Reason for deleting {{ $child->first_name }}</label>
                                                    <input type="text" name="reason" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-sm btn-danger">Confirm Delete</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if ($parent->guardianOfChildren->isNotEmpty())
                        <hr>
                        <h3 class="h6">Co-parent of</h3>
                        <ul class="mb-0 small">
                            @foreach ($parent->guardianOfChildren as $child)
                                <li>{{ $child->first_name }} — registered by {{ optional($child->owner)->full_name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 mb-3">Also offers</h2>
                    <p class="text-muted small">
                        Support beyond being a parent — the same list the supporters register uses.
                    </p>
                    <form method="POST" action="{{ route('admin.parents.categories', $parent) }}">
                        @csrf
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                       value="{{ $category->id }}" id="pcat-{{ $category->id }}"
                                       @checked($parent->categories->contains($category->id))>
                                <label class="form-check-label" for="pcat-{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-sm btn-primary mt-3">Save</button>
                    </form>
                </div>
            </div>

            @unless ($parent->hasCompletedRegistration())
                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-body">
                        <h2 class="h6">Registration not finished</h2>
                        <p class="small text-muted">
                            Invited {{ optional($parent->invited_at ?? $parent->created_at)->diffForHumans() }} and
                            still no password, so they can't sign in.
                            @if ($parent->pending_reminder_count > 0)
                                {{ $parent->pending_reminder_count }}
                                {{ Str::plural('reminder', $parent->pending_reminder_count) }} sent so far.
                            @endif
                            Resending starts their 3/6/9 clock again from today.
                        </p>
                        <form method="POST" action="{{ route('admin.parents.resend-invite', $parent) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">Resend Invite</button>
                        </form>
                    </div>
                </div>
            @endunless

            <div class="card shadow-sm mb-4 border-danger">
                <div class="card-body">
                    <h2 class="h6 text-danger">Danger Zone</h2>

                    @if ($parent->suspended_at)
                        <form method="POST" action="{{ route('admin.parents.reactivate', $parent) }}" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Reactivate</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.parents.suspend', $parent) }}" class="mb-3">
                            @csrf
                            <input type="text" name="reason" class="form-control form-control-sm mb-2"
                                   placeholder="Reason for suspending" required>
                            <button type="submit" class="btn btn-sm btn-outline-warning">Suspend</button>
                        </form>
                    @endif

                    <hr>

                    <p class="small text-muted">
                        Deleting removes this parent entirely — their details, children, co-parent links and
                        category tags. It cannot be undone.
                    </p>
                    <form method="POST" action="{{ route('admin.parents.destroy', $parent) }}">
                        @csrf @method('DELETE')
                        <input type="text" name="reason" class="form-control form-control-sm mb-2"
                               placeholder="Reason for deleting" required>
                        <input type="text" name="confirm_email" class="form-control form-control-sm mb-2"
                               placeholder="Type {{ $parent->email }} to confirm" required>
                        <button type="submit" class="btn btn-sm btn-danger">Delete Parent</button>
                    </form>
                </div>
            </div>

            @if ($auditLog->isNotEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h6">Admin History</h2>
                        <ul class="list-unstyled small mb-0">
                            @foreach ($auditLog as $entry)
                                <li class="mb-2">
                                    <strong>{{ $entry->action }}</strong>
                                    <span class="text-muted">{{ $entry->created_at->format('Y-m-d H:i') }}</span>
                                    @if ($entry->reason)
                                        <span class="d-block text-muted">{{ $entry->reason }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
