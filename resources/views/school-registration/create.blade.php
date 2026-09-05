@extends('layouts.app')

@php($pageTitle = 'Add My School')

@section('content')
    <section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h1 class="display-6 fw-bold mb-3">Add My School</h1>
                    <p class="lead text-muted mb-3">
                        This is a parent-led initiative. Parents can request that a school be added even if the school
                        itself has not yet engaged. Schools are also welcome to get in touch and support the
                        initiative, but there is no obligation on any school to take part.
                    </p>
                    <p class="text-muted mb-0">
                        If a school later chooses to support the initiative, it can be given secure access to update
                        class sizes and school contact details.
                    </p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('school-registration.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="school_name" class="form-label">School Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="school_name"
                                    name="school_name"
                                    value="{{ old('school_name') }}"
                                    required
                                >
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
                                <label for="website_url" class="form-label">School Website</label>
                                <input
                                    type="url"
                                    class="form-control"
                                    id="website_url"
                                    name="website_url"
                                    value="{{ old('website_url') }}"
                                    placeholder="https://example.ie"
                                >
                            </div>

                            <hr class="my-4">

                            <h2 class="h5 mb-3">Your Contact Details</h2>

                            <div class="mb-3">
                                <label for="contact_name" class="form-label">Your Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="contact_name"
                                    name="contact_name"
                                    value="{{ old('contact_name') }}"
                                    required
                                >
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label">Your Email</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="contact_email"
                                        name="contact_email"
                                        value="{{ old('contact_email') }}"
                                        required
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label">Your Phone</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="contact_phone"
                                        name="contact_phone"
                                        value="{{ old('contact_phone') }}"
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
                                >{{ old('notes') }}</textarea>
                                <div class="form-text">
                                    You can use this to explain whether you are a parent, a school contact, or anything
                                    else that would be helpful.
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Submit School Request</button>
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
