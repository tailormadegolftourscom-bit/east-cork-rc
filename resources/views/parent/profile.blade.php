@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h3 mb-3">Edit Profile</h1>

                    <p class="lead text-muted mb-4">
                        Update your contact preferences and public display settings.
                    </p>

                    <form method="POST" action="{{ route('parent.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="preferred_contact_method" class="form-label">Preferred Contact Method</label>
                            <select name="preferred_contact_method" id="preferred_contact_method" class="form-select">
                                <option value="email" {{ old('preferred_contact_method', $person->preferred_contact_method) === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="sms" {{ old('preferred_contact_method', $person->preferred_contact_method) === 'sms' ? 'selected' : '' }}>Regular Text</option>
                                <option value="whatsapp" {{ old('preferred_contact_method', $person->preferred_contact_method) === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            </select>
                            @error('preferred_contact_method')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                class="form-control"
                                value="{{ old('phone', $person->phone) }}"
                            >
                            @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="public_name_mode" class="form-label">Public Name Display</label>
                            <select name="public_name_mode" id="public_name_mode" class="form-select">
                                <option value="real_name" {{ old('public_name_mode', $person->public_name_mode) === 'real_name' ? 'selected' : '' }}>Use my real name</option>
                                <option value="anon_code" {{ old('public_name_mode', $person->public_name_mode) === 'anon_code' ? 'selected' : '' }}>Use anonymous code</option>
                            </select>
                            @error('public_name_mode')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
