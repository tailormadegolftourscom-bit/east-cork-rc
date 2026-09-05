@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $school->name }}: Classes</h1>
            <p class="text-muted mb-0">Manage classes and pupil totals.</p>
        </div>

        <a href="{{ route('admin.schools.classes.create', $school) }}" class="btn btn-primary">
            Add Class
        </a>
    </div>

    <div class="mb-3">
        <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary btn-sm">Back to Schools</a>
    </div>

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
                                <td>
                                    <span class="badge {{ $class->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $class->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.schools.classes.edit', [$school, $class]) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.schools.classes.destroy', [$school, $class]) }}"
                                              onsubmit="return confirm('Delete this class?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
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
