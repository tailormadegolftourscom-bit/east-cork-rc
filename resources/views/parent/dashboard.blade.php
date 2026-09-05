@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h3 mb-3">Parent Dashboard</h1>

                    <p class="lead text-muted mb-4">
                        Manage your profile and register your child or children.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <dl class="row mb-4">
                        <dt class="col-sm-4">Support Status</dt>
                        <dd class="col-sm-8">
                            {{ ucfirst(optional($supporter)->support_status ?? 'supporting') }}
                        </dd>

                        <dt class="col-sm-4">Preferred Contact</dt>
                        <dd class="col-sm-8">
                            {{ ucfirst($person->preferred_contact_method ?? 'email') }}
                        </dd>

                        <dt class="col-sm-4">Public Name</dt>
                        <dd class="col-sm-8">
                            {{ $person->public_display_name }}
                        </dd>

                        @if(!empty($person->email))
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">
                                {{ $person->email }}
                            </dd>
                        @endif

                        @if(!empty($person->phone))
                            <dt class="col-sm-4">Phone</dt>
                            <dd class="col-sm-8">
                                {{ $person->phone }}
                            </dd>
                        @endif
                    </dl>

                    <div class="d-flex flex-wrap gap-2 mb-5">
                        <a href="{{ route('parent.profile.edit') }}" class="btn btn-outline-primary">
                            Edit My Profile
                        </a>

                        <a href="/parent/children/create" class="btn btn-primary">
                            Register a Child
                        </a>

                        <a href="{{ route('parent.co-parent.create') }}" class="btn btn-outline-primary">
                            Add a Co-Parent
                        </a>
                    </div>

                    <h2 class="h5 mb-3">Registered Children</h2>

                    @if($children->isEmpty())
                        <div class="alert alert-light border mb-0">
                            <p class="mb-2">You have not registered any children yet.</p>
                            <a href="/parent/children/create" class="btn btn-sm btn-primary">
                                Add Your First Child
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Code Name</th>
                                    <th>School</th>
                                    <th>Class</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($children as $child)
                                    <tr>
                                        <td>{{ $child->first_name }}</td>
                                        <td>{{ $child->last_name ?: '—' }}</td>
                                        <td>
                                            @if($child->public_label && $child->public_label !== 'anonymous')
                                                <span class="badge bg-light text-dark border">{{ $child->public_label }}</span>
                                            @else
                                                <span class="text-muted small">Not set</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($child->schoolLink?->currentSchool)->name ?: 'Not set' }}</td>
                                        <td>{{ optional($child->schoolLink?->currentSchoolClass)->display_name ?: '—' }}</td>
                                        <td>
                                            @if($child->parent_person_id !== $person->id)
                                                <span class="badge bg-light text-dark border">Shared by {{ $child->parentPerson->public_display_name }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('parent.children.edit', $child) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <a href="/parent/children/create" class="btn btn-primary">
                                Register Another Child
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
