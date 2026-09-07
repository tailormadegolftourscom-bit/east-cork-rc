@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Classes</h1>
            <p class="text-muted mb-0">
                {{ $school->name }}
                &middot; <span class="fw-semibold">{{ $totalRegistered }}</span> {{ Str::plural('child', $totalRegistered) }} registered so far
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('school.dashboard') }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
            <a href="{{ route('school.classes.create') }}" class="btn btn-primary">
                Add Class
            </a>
        </div>
    </div>

    @if ($school->classes_confirmed)
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <span>&check; You've confirmed this class list is accurate.</span>
        </div>
    @else
        <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>
                This class list started from a standard Junior Infants&ndash;6th Class template. If it matches
                your school (including any separate streams, e.g. multiple classes per year), confirm it below.
            </span>
            <form method="POST" action="{{ route('school.classes.confirm') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary text-nowrap">Confirm This Is Accurate</button>
            </form>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($classes->isEmpty())
                <p class="mb-0">No classes created yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Display Name</th>
                            <th>Class Level</th>
                            <th>Identifier</th>
                            <th>Total Pupils</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($classes as $class)
                            <tr>
                                <td>{{ $class->display_name }}</td>
                                <td>{{ str_replace('_', ' ', $class->class_level) }}</td>
                                <td>{{ $class->class_stream ?: '—' }}</td>
                                <td>{{ $class->total_pupils ?? '—' }}</td>
                                <td>{{ $class->registered_children_count }}</td>
                                <td>
                                    <span class="badge {{ $class->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $class->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('school.classes.edit', $class) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
