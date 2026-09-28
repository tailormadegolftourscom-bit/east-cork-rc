@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3">Parent Registration</h1>

                    <div class="alert alert-info small">
                        <strong>Not a parent of a child in a local school?</strong> Grandparents, aunts and uncles,
                        neighbours, teachers and coaches are all welcome &mdash;
                        <a href="{{ route('supporters.create') }}" class="alert-link">join as a supporter instead</a>.
                        No password needed.
                    </div>

                    <form method="POST" action="/register">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First name (Parent)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last name (Parent)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    required
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                            >
                        </div>

                        <div class="alert alert-light border small">
                            <strong>Your privacy.</strong> Your email address is used to run your account and is never
                            shown to anyone else. After registering, you choose whether your real name or an anonymous
                            code is shown if you join a group, and how you'd like to be contacted. ECRC's administrators
                            can see your details so the site can be run properly.
                            <a href="{{ route('privacy') }}" target="_blank">Read our Privacy &amp; Data Protection Notice</a>.
                        </div>

                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
