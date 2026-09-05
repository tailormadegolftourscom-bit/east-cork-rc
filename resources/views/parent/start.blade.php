@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h4 mb-3">Complete Your Parent Profile</h1>

                    <p class="text-muted mb-4">
                        Your account is verified. Complete your profile below to continue.
                    </p>

                    <form method="POST" action="{{ route('parent.start.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="preferred_contact_method" class="form-label">Preferred Contact Method</label>
                            <select class="form-select" id="preferred_contact_method" name="preferred_contact_method" required>
                                <option value="email" @selected(old('preferred_contact_method', $person->preferred_contact_method) === 'email')>Email</option>
                                <option value="sms" @selected(old('preferred_contact_method', $person->preferred_contact_method) === 'sms')>SMS</option>
                                <option value="whatsapp" @selected(old('preferred_contact_method', $person->preferred_contact_method) === 'whatsapp')>WhatsApp</option>
                            </select>
                            <div class="form-text">
                                If you choose SMS or WhatsApp, a phone number is required.
                            </div>
                            @error('preferred_contact_method')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="text"
                                class="form-control"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $person->phone) }}"
                            >
                            @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="public_name_mode" class="form-label">Public Name Preference</label>
                            <select class="form-select" id="public_name_mode" name="public_name_mode" required>
                                <option value="real_name" @selected(old('public_name_mode', $person->public_name_mode) === 'real_name')>Show my real name on public pages</option>
                                <option value="anon_code" @selected(old('public_name_mode', $person->public_name_mode) === 'anon_code')>Show an anonymous supporter code instead</option>
                            </select>
                            @error('public_name_mode')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save and Continue</button>
                            <a href="/" class="btn btn-outline-secondary">Do This Later</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <p class="mb-0 text-muted">
                        You can add your child or children after saving your profile.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
