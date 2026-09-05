@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $registrationRequest->school_name }}</h1>
            <p class="text-muted mb-0">School registration request details</p>
        </div>

        <a href="{{ route('admin.school-requests.index') }}" class="btn btn-outline-secondary">
            Back to Requests
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">School Details</h2>

                    <dl class="row mb-0">
                        <dt class="col-sm-4">School Name</dt>
                        <dd class="col-sm-8">{{ $registrationRequest->school_name }}</dd>

                        <dt class="col-sm-4">School Type</dt>
                        <dd class="col-sm-8">{{ ucfirst($registrationRequest->school_type) }}</dd>

                        <dt class="col-sm-4">Town</dt>
                        <dd class="col-sm-8">{{ $registrationRequest->town ?: '—' }}</dd>

                        <dt class="col-sm-4">Website</dt>
                        <dd class="col-sm-8">
                            @if ($registrationRequest->website_url)
                                <a href="{{ $registrationRequest->website_url }}" target="_blank">{{ $registrationRequest->website_url }}</a>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-4">Submitted</dt>
                        <dd class="col-sm-8">{{ $registrationRequest->created_at?->format('d M Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Contact Details</h2>

                    <dl class="row mb-0">
                        <dt class="col-sm-4">Contact Name</dt>
                        <dd class="col-sm-8">{{ $registrationRequest->contact_name }}</dd>

                        <dt class="col-sm-4">Contact Email</dt>
                        <dd class="col-sm-8">{{ $registrationRequest->contact_email }}</dd>

                        <dt class="col-sm-4">Contact Phone</dt>
                        <dd class="col-sm-8">{{ $registrationRequest->contact_phone ?: '—' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Notes</h2>

                    @if ($registrationRequest->notes)
                        <p class="mb-0">{{ $registrationRequest->notes }}</p>
                    @else
                        <p class="text-muted mb-0">No notes provided.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Request Status</h2>

                    <p>
                        <span class="badge
                            @if ($registrationRequest->request_status === 'pending') text-bg-warning
                            @elseif ($registrationRequest->request_status === 'approved') text-bg-success
                            @else text-bg-secondary
                            @endif">
                            {{ ucfirst($registrationRequest->request_status) }}
                        </span>
                    </p>

                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.school-requests.update-status', $registrationRequest) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="request_status" value="pending">
                            <button type="submit" class="btn btn-outline-warning w-100">Mark Pending</button>
                        </form>

                        <form method="POST" action="{{ route('admin.school-requests.update-status', $registrationRequest) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="request_status" value="approved">
                            <button type="submit" class="btn btn-success w-100">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('admin.school-requests.update-status', $registrationRequest) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="request_status" value="rejected">
                            <button type="submit" class="btn btn-outline-danger w-100">Reject</button>
                        </form>
                    </div>

                    <hr>

                    @if (! $registrationRequest->created_school_id)
                        <hr>

                        <h2 class="h5 mb-3">Create School</h2>

                        <form method="POST" action="{{ route('admin.school-requests.create-school', $registrationRequest) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="slug" class="form-label">School Slug</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="slug"
                                    name="slug"
                                    value="{{ old('slug', \Illuminate\Support\Str::slug($registrationRequest->school_name)) }}"
                                    required
                                >
                                <div class="form-text">Lowercase letters, numbers and hyphens only.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Create School from Request</button>
                        </form>
                    @else
                        <hr>

                        <h2 class="h5 mb-3">Created School</h2>
                        <p class="mb-2">This request has already created a school record.</p>
                        <a href="{{ route('admin.schools.edit', $registrationRequest->created_school_id) }}" class="btn btn-outline-primary w-100">
                            Open School Record
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
