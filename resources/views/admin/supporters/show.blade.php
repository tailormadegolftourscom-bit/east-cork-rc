@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $person->first_name }} {{ $person->last_name }}</h1>
        <a href="{{ route('admin.supporters.index') }}" class="btn btn-outline-secondary btn-sm">Back to Supporters</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h6">Contact &amp; Preferences</h2>
            <dl class="row mb-0">
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $person->email }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $person->phone ?: '—' }}</dd>

                <dt class="col-sm-3">Preferred Contact</dt>
                <dd class="col-sm-9">{{ ucfirst($person->preferred_contact_method) }}</dd>

                <dt class="col-sm-3">Public Name Mode</dt>
                <dd class="col-sm-9">
                    {{ $person->public_name_mode === 'anon_code' ? 'Anonymous (shown publicly as ' . $person->public_display_name . ')' : 'Real name shown publicly' }}
                </dd>

                @if ($person->supporter)
                    <dt class="col-sm-3">Support Status</dt>
                    <dd class="col-sm-9">{{ ucfirst($person->supporter->support_status) }}</dd>

                    <dt class="col-sm-3">Joined</dt>
                    <dd class="col-sm-9">{{ optional($person->supporter->joined_at)->format('Y-m-d') }}</dd>
                @endif
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h6 mb-3">Children</h2>

            @if ($person->children->isEmpty())
                <p class="text-muted mb-0">No children registered.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>School</th>
                            <th>Class</th>
                            <th>Audit Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($person->children as $child)
                            <tr>
                                <td>{{ $child->first_name }}</td>
                                <td>{{ $child->last_name ?: '—' }}</td>
                                <td>{{ optional($child->schoolLink?->currentSchool)->name ?: 'Not set' }}</td>
                                <td>{{ optional($child->schoolLink?->currentSchoolClass)->display_name ?: '—' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.children.update-audit-status', $child) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="audit_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" @selected($child->audit_status === 'pending')>Pending</option>
                                            <option value="reviewed" @selected($child->audit_status === 'reviewed')>Reviewed</option>
                                            <option value="verified" @selected($child->audit_status === 'verified')>Verified</option>
                                        </select>
                                    </form>
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
