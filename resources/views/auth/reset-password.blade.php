@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3">Set Your Password</h1>

                    {{-- The invalid-token error is the one people actually hit: links time
                         out, and an unusable form with an error above it is a dead end
                         unless you say plainly what to do next. --}}
                    @php($tokenExpired = collect($errors->all())->contains(fn ($m) => Str::contains($m, 'token')))

                    @if ($tokenExpired)
                        <div class="alert alert-warning">
                            <strong>That link has expired.</strong>
                            Links last 24 hours, so an older email won't work any more. Ask for a fresh one
                            and it'll arrive in a moment — nothing has gone wrong with your account.

                            <form method="POST" action="/forgot-password" class="mt-3">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
                                <button type="submit" class="btn btn-warning btn-sm">
                                    Email Me a New Link
                                </button>
                            </form>
                        </div>
                    @endif

                    <form method="POST" action="/reset-password">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">Set Password</button>
                    </form>

                    <p class="small text-muted mt-3 mb-0">
                        Link not working? <a href="/forgot-password">Ask for a new one</a> — they expire after
                        24 hours.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
