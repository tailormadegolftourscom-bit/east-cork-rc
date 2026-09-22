@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Committees</h1>
        <div>
            <a href="{{ route('admin.committees.create') }}" class="btn btn-primary btn-sm">Add Committee</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
        </div>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    @if ($withoutConvenor->isNotEmpty())
        <div class="alert alert-warning">
            <strong>{{ $withoutConvenor->count() }}</strong>
            {{ Str::plural('committee', $withoutConvenor->count()) }} with no convenor. These show a
            <em>Become Convenor</em> button on the <a href="{{ route('committees.index') }}">public page</a>.
        </div>
    @endif

    @foreach ($types as $key => $label)
        @php($group = $byType[$key] ?? collect())
        @continue($group->isEmpty())

        <h2 class="h5 mt-4">{{ $label }} ({{ $group->count() }})</h2>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr><th>Name</th><th>Attached to</th><th>Convenor</th><th>Members</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($group as $committee)
                    @php($convenor = $committee->members->firstWhere('role', 'convenor'))
                    <tr class="{{ $convenor ? '' : 'table-warning' }}">
                        <td>
                            <a href="{{ route('committees.show', $committee) }}">{{ $committee->name }}</a>
                            <div class="small text-muted">{{ $committee->slug }}</div>
                        </td>
                        <td class="small">
                            {{ optional($committee->school)->name ?: '—' }}
                            @if ($committee->schoolClass)
                                <span class="text-muted">/ {{ $committee->schoolClass->display_name }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $convenor ? $convenor->display_name : '' }}
                            @unless ($convenor)
                                <span class="badge text-bg-warning">Vacant</span>
                            @endunless
                        </td>
                        <td>{{ $committee->members->count() }}</td>
                        <td>{{ ucfirst($committee->status) }}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="collapse" data-bs-target="#del-cttee-{{ $committee->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse" id="del-cttee-{{ $committee->id }}">
                        <td colspan="6" class="bg-light">
                            <form method="POST" action="{{ route('admin.committees.destroy', $committee) }}"
                                  class="row g-2 align-items-end">
                                @csrf @method('DELETE')
                                <div class="col">
                                    <label class="form-label small">
                                        Reason for deleting {{ $committee->name }}
                                        @if ($committee->members->isNotEmpty())
                                            <span class="text-danger">
                                                — this removes {{ $committee->members->count() }}
                                                {{ Str::plural('member', $committee->members->count()) }} too
                                            </span>
                                        @endif
                                    </label>
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
    @endforeach
@endsection
