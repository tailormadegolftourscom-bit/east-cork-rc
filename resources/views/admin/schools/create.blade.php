@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Add School</h1>
                    <p class="text-muted mb-0">Create a new primary or secondary school record.</p>
                </div>

                <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                    Back to Schools
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.schools.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">School Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="alternative_name" class="form-label">Alternative Name <span class="text-muted">(optional)</span></label>
                            <input
                                type="text"
                                class="form-control"
                                id="alternative_name"
                                name="alternative_name"
                                value="{{ old('alternative_name') }}"
                            >
                            <div class="form-text">An Irish-language name or other commonly-used alternative, if any.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="slug"
                                    name="slug"
                                    value="{{ old('slug') }}"
                                    required
                                >
                                <div class="form-text">
                                    Use lowercase letters, numbers, and hyphens only. Example: midleton-college.
                                    This becomes permanent once set &mdash; it's used for the school committee slug and derived identifiers.
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="roll_number" class="form-label">Roll Number <span class="text-muted">(optional)</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="roll_number"
                                    name="roll_number"
                                    value="{{ old('roll_number') }}"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="school_type" class="form-label">School Type</label>
                                <select class="form-select" id="school_type" name="school_type" required>
                                    <option value="">Select type</option>
                                    <option value="primary" @selected(old('school_type') === 'primary')>Primary</option>
                                    <option value="secondary" @selected(old('school_type') === 'secondary')>Secondary</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="town" class="form-label">Town</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="town"
                                    name="town"
                                    value="{{ old('town') }}"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="website_url" class="form-label">Website URL</label>
                            <input
                                type="url"
                                class="form-control"
                                id="website_url"
                                name="website_url"
                                value="{{ old('website_url') }}"
                                placeholder="https://example.ie"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="school_phone" class="form-label">School Telephone</label>
                            <input
                                type="text"
                                class="form-control"
                                id="school_phone"
                                name="school_phone"
                                value="{{ old('school_phone') }}"
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
                                    value="{{ old('principal_name') }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="principal_email" class="form-label">Principal Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="principal_email"
                                    name="principal_email"
                                    value="{{ old('principal_email') }}"
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
                                    value="{{ old('vice_principal_name') }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vice_principal_email" class="form-label">Vice Principal Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="vice_principal_email"
                                    name="vice_principal_email"
                                    value="{{ old('vice_principal_email') }}"
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
                                    value="{{ old('secretary_name') }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="secretary_email" class="form-label">Secretary Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="secretary_email"
                                    name="secretary_email"
                                    value="{{ old('secretary_email') }}"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="support_status" class="form-label">Support Status</label>
                                <select class="form-select" id="support_status" name="support_status" required>
                                    <option value="undecided" @selected(old('support_status', 'undecided') === 'undecided')>Undecided</option>
                                    <option value="supporting" @selected(old('support_status') === 'supporting')>Supporting</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Record Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="inactive" @selected(old('status', 'inactive') === 'inactive')>Inactive</option>
                                    <option value="active" @selected(old('status') === 'active')>Active</option>
                                </select>
                                <div class="form-text">New schools start inactive until class and pupil details are in place.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea
                                class="form-control"
                                id="notes"
                                name="notes"
                                rows="4"
                            >{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save School</button>
                            <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
