@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Schools</h1>
            <p class="text-muted mb-0">Manage primary and secondary schools in the initiative.</p>
        </div>

        <a href="{{ route('admin.schools.create') }}" class="btn btn-primary">
            Add School
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($schools->isEmpty())
                <div class="text-center py-5">
                    <h2 class="h5 mb-2">No schools yet</h2>
                    <p class="text-muted mb-3">Add your first school to get started.</p>
                    <a href="{{ route('admin.schools.create') }}" class="btn btn-primary">Add School</a>
                </div>
            @else
                <div class="text-muted mb-3">
                    Total schools: <strong>{{ $schools->count() }}</strong>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>School</th>
                            <th>Type</th>
                            <th>Town</th>
                            <th>Committee</th>
                            <th>Principal</th>
                            <th>Vice Principal</th>
                            <th>Secretary</th>
                            <th>Support</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($schools as $school)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $school->name }}</div>

                                    @if ($school->slug)
                                        <div class="small text-muted">{{ $school->slug }}</div>
                                    @endif

                                    @if ($school->school_phone)
                                        <div class="small text-muted">Tel: {{ $school->school_phone }}</div>
                                    @endif

                                    @if ($school->website_url)
                                        <div>
                                            <a href="{{ $school->website_url }}" target="_blank" class="small text-decoration-none">
                                                Website
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                        <span class="badge {{ $school->school_type === 'primary' ? 'text-bg-primary' : 'bg-dark' }}">
                                            {{ ucfirst($school->school_type) }}
                                        </span>
                                </td>

                                <td>{{ $school->town ?: '—' }}</td>

                                <td>
                                    @if ($school->committee)
                                        <div class="fw-semibold">{{ $school->committee->name }}</div>
                                        <div class="small text-muted">{{ $school->committee->slug }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($school->principal_name)
                                        <div>{{ $school->principal_name }}</div>
                                    @endif

                                    @if ($school->principal_email)
                                        <div class="small text-muted">{{ $school->principal_email }}</div>
                                    @elseif (! $school->principal_name)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($school->vice_principal_name)
                                        <div>{{ $school->vice_principal_name }}</div>
                                    @endif

                                    @if ($school->vice_principal_email)
                                        <div class="small text-muted">{{ $school->vice_principal_email }}</div>
                                    @elseif (! $school->vice_principal_name)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($school->secretary_name)
                                        <div>{{ $school->secretary_name }}</div>
                                    @endif

                                    @if ($school->secretary_email)
                                        <div class="small text-muted">{{ $school->secretary_email }}</div>
                                    @elseif (! $school->secretary_name)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                        <span class="badge {{ $school->support_status === 'supporting' ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ ucfirst($school->support_status) }}
                                        </span>
                                </td>

                                <td>
                                        <span class="badge {{ $school->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ ucfirst($school->status) }}
                                        </span>

                                    @if ($school->status === 'inactive')
                                        <div class="small mt-1">
                                            @if ($school->isReadyForActivation())
                                                <span class="text-success">Ready to activate</span>
                                            @else
                                                <span class="text-muted">Pupil totals incomplete</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                                        <a href="{{ route('admin.schools.classes.index', $school) }}" class="btn btn-sm btn-outline-secondary">
                                            Classes
                                        </a>

                                        <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.schools.destroy', $school) }}" onsubmit="return confirm('Delete this school?');">
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
