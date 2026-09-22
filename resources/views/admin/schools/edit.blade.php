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

                    @if (! $school->principal_email)
                        <div class="alert alert-warning mb-0">
                            Enter a principal email address before sending the school invite.
                        </div>

                    @elseif (! $school->inviteSent())
                        <p class="text-muted mb-3">
                            This writes to <strong>{{ $school->principal_email }}</strong>
                            @if ($school->secretary_email)
                                , copied to {{ $school->secretary_email }}
                            @endif
                            introducing the initiative, and sends a separate link to set a password.
                            It hasn't been sent yet.
                        </p>
                        <form method="POST" action="{{ route('admin.schools.send-invite', $school) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">Send School Invite</button>
                        </form>

                    @else
                        @php($days = $school->daysSinceInvite())
                        @php($answered = $school->inviteAnswered())
                        @php($registered = (bool) $school->account?->registration_completed_at)

                        <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                            <span class="badge {{ $answered ? 'text-bg-success' : ($days > 14 ? 'text-bg-warning' : 'text-bg-secondary') }}">
                                Invite sent {{ $school->invite_sent_at->format('j M Y') }}
                            </span>
                            <span class="text-muted small">
                                {{ $days === 0 ? 'today' : $days.' '.Str::plural('day', $days).' ago' }}
                                @unless ($answered)
                                    &middot; no reply yet
                                @endunless
                            </span>
                        </div>

                        @if ($registered)
                            <div class="alert alert-success">
                                <strong>The school set up its account.</strong>
                                {{ $school->account->email }} signed in and chose a password
                                {{ $school->account->registration_completed_at->diffForHumans() }}.
                            </div>
                        @elseif ($school->invite_response_at)
                            <div class="alert alert-success">
                                <strong>Response received</strong>
                                {{ $school->invite_response_at->format('j M Y') }}.
                                @if ($school->invite_response_note)
                                    <span class="d-block">{{ $school->invite_response_note }}</span>
                                @endif
                                <span class="d-block small text-muted">
                                    They haven't set a password yet, so the school area is still unused.
                                </span>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.schools.invite-response', $school) }}"
                                  class="row g-2 align-items-end mb-3">
                                @csrf
                                <div class="col-md">
                                    <label for="invite_response_note" class="form-label small">
                                        Heard back from them? Note how, if you like.
                                    </label>
                                    <input type="text" name="invite_response_note" id="invite_response_note"
                                           class="form-control form-control-sm"
                                           placeholder="e.g. principal rang, happy to be listed">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success btn-sm">Mark Response Received</button>
                                </div>
                            </form>
                        @endif

                        <button type="button" class="btn btn-link btn-sm text-muted p-0"
                                data-bs-toggle="collapse" data-bs-target="#resend-invite">
                            Need to send it again?
                        </button>

                        <div class="collapse mt-2" id="resend-invite">
                            <form method="POST" action="{{ route('admin.schools.send-invite', $school) }}"
                                  class="row g-2 align-items-end">
                                @csrf
                                <div class="col-md">
                                    <label for="resend_reason" class="form-label small">
                                        Why is it going again? This writes to the principal a second time.
                                    </label>
                                    <input type="text" name="resend_reason" id="resend_reason"
                                           class="form-control form-control-sm"
                                           placeholder="e.g. wrong address first time" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Resend Invite</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
