@php($collapseId = 'classes-' . $school->id)

<div class="col-lg-6">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h3 class="h5 mb-0">{{ $school->name }}</h3>

                <span class="badge {{ $school->support_status === 'supporting' ? 'text-bg-success' : 'text-bg-warning' }}">
                    {{ $school->support_status === 'supporting' ? 'School Supporting' : 'School Undecided' }}
                </span>
            </div>

            <p class="text-muted mb-2">
                {{ $school->town ?: 'East Cork' }}
            </p>

            <p class="mb-2">
                <span class="badge bg-light text-dark border">
                    {{ $school->registered_children_count }}
                    {{ Str::plural('child', $school->registered_children_count) }} registered
                </span>
            </p>

            @if ($school->school_phone)
                <p class="mb-1 small text-muted">Tel: {{ $school->school_phone }}</p>
            @endif

            @if ($school->website_url)
                <p class="mb-2">
                    <a href="{{ $school->website_url }}" target="_blank" class="text-decoration-none">
                        School website
                    </a>
                </p>
            @endif

            @if ($school->classes->isNotEmpty())
                <button
                    class="btn btn-sm btn-outline-secondary"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                >
                    See classes
                </button>

                <div class="collapse mt-3" id="{{ $collapseId }}">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Class</th>
                            <th class="text-end">Registered</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($school->classes as $class)
                            <tr>
                                <td>{{ $class->display_name }}</td>
                                <td class="text-end">{{ $class->registered_children_count }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
