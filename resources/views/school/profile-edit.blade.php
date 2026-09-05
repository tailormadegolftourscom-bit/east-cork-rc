@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Edit School Details</h1>
                    <p class="text-muted mb-0">{{ $school->name }}</p>
                </div>

                <a href="{{ route('school.dashboard') }}" class="btn btn-outline-secondary">
                    Back to Dashboard
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('school.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">School Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value="{{ old('name', $school->name) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="support_status" class="form-label">School Support Status</label>
                            <select class="form-select" id="support_status" name="support_status" required>
                                <option value="undecided" @selected(old('support_status', $school->support_status) === 'undecided')>
                                    Undecided
                                </option>
                                <option value="supporting" @selected(old('support_status', $school->support_status) === 'supporting')>
                                    Supporting
                                </option>
                            </select>
                            <div class="form-text">
                                This indicates whether the school wishes to support the initiative.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="town" class="form-label">Town</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="town"
                                    name="town"
                                    value="{{ old('town', $school->town) }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="website_url" class="form-label">Website URL</label>
                                <input
                                    type="url"
                                    class="form-control"
                                    id="website_url"
                                    name="website_url"
                                    value="{{ old('website_url', $school->website_url) }}"
                                >
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

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea
                                class="form-control"
                                id="notes"
                                name="notes"
                                rows="4"
                            >{{ old('notes', $school->notes) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('school.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
