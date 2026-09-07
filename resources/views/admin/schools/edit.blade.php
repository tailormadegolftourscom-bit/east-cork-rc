@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Edit School</h1>
                    <p class="text-muted mb-0">Update this school record.</p>
                </div>

                <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                    Back to Schools
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.schools.update', $school) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">School Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="alternative_name" class="form-label">Alternative Name <span class="text-muted">(optional)</span></label>
                            <input
                                type="text"
                                class="form-control"
                                id="alternative_name"
                                name="alternative_name"
                                value="{{ old('alternative_name', $school->alternative_name) }}"
                            >
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="slug"
                                    name="slug"
                                    value="{{ old('slug', $school->slug) }}"
                                    required
                                >
                                <div class="form-text text-warning">
                                    Changing this after the school is live affects the school committee slug and any
                                    derived identifiers already shared. Only change it if absolutely necessary.
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="roll_number" class="form-label">Roll Number <span class="text-muted">(optional)</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="roll_number"
                                    name="roll_number"
                                    value="{{ old('roll_number', $school->roll_number) }}"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="school_type" class="form-label">School Type</label>
                                <select class="form-select" id="school_type" name="school_type" required>
                                    <option value="primary" @selected(old('school_type', $school->school_type) === 'primary')>Primary</option>
                                    <option value="secondary" @selected(old('school_type', $school->school_type) === 'secondary')>Secondary</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="town" class="form-label">Town</label>
                                <input type="text" class="form-control" id="town" name="town" value="{{ old('town', $school->town) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="website_url" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="website_url" name="website_url" value="{{ old('website_url', $school->website_url) }}">
                        </div>

                        <div class="mb-3">
                            <label for="school_phone" class="form-label">School Telephone</label>
                            <input
                                type="text"
                                class="form-control"
                                id="school_phone"
                                name="school_phone"
                                value="{{ old('school_phone', $school->school_phone) }}"
                            >
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="principal_name" class="form-label">Principal Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="principal_name"
                                    name="principal_name"
                                    value="{{ old('principal_name', $school->principal_name) }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="principal_email" class="form-label">Principal Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="principal_email"
                                    name="principal_email"
                                    value="{{ old('principal_email', $school->principal_email) }}"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vice_principal_name" class="form-label">Vice Principal Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="vice_principal_name"
                                    name="vice_principal_name"
                                    value="{{ old('vice_principal_name', $school->vice_principal_name) }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vice_principal_email" class="form-label">Vice Principal Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="vice_principal_email"
                                    name="vice_principal_email"
                                    value="{{ old('vice_principal_email', $school->vice_principal_email) }}"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="secretary_name" class="form-label">Secretary Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="secretary_name"
                                    name="secretary_name"
                                    value="{{ old('secretary_name', $school->secretary_name) }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="secretary_email" class="form-label">Secretary Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="secretary_email"
                                    name="secretary_email"
                                    value="{{ old('secretary_email', $school->secretary_email) }}"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            @if ($school->isReadyForActivation())
                                <div class="alert alert-success mb-0">
                                    <strong>Ready for activation</strong> &mdash; every active class has a pupil total recorded.
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <strong>Not yet ready for activation.</strong>
                                    @php($missing = $school->classesMissingPupilTotals())
                                    @if ($missing->isEmpty())
                                        <div>No active classes have been set up yet.</div>
                                    @else
                                        <div>Missing pupil totals for: {{ $missing->pluck('display_name')->join(', ') }}.</div>
                                    @endif
                                    <a href="{{ route('admin.schools.classes.index', $school) }}">Manage classes</a>
                                    &mdash; this is guidance only; you can still activate manually.
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="support_status" class="form-label">Support Status</label>
                                <select class="form-select" id="support_status" name="support_status" required>
                                    <option value="undecided" @selected(old('support_status', $school->support_status) === 'undecided')>Undecided</option>
                                    <option value="supporting" @selected(old('support_status', $school->support_status) === 'supporting')>Supporting</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Record Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" @selected(old('status', $school->status) === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $school->status) === 'inactive')>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="classes_confirmed" name="classes_confirmed"
                                   value="1" @checked(old('classes_confirmed', $school->classes_confirmed))>
                            <label class="form-check-label" for="classes_confirmed">
                                Class list confirmed accurate (exact number of classes/streams verified —
                                either the school confirmed it themselves, or you've checked it against a list
                                they sent)
                            </label>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes', $school->notes) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update School</button>
                            <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 bg-light mt-4" id="school-user-access">
                <div class="card-body">
                    <h2 class="h5 mb-2">School User Access</h2>
                    <p class="text-muted mb-3">
                        Send a school invite after the contact details have been entered.
                        The invite will go to the principal email address and can be copied to the secretary.
                    </p>

                    @if ($school->principal_email)
                        <form method="POST" action="{{ route('admin.schools.send-invite', $school) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                Send School Invite
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning mb-0">
                            Enter a principal email address before sending the school invite.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
