@extends('layouts.app')

@php($pageTitle = 'Add a Co-Parent — East Cork Reclaim Childhood')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h4 mb-3">Add a Co-Parent</h1>

                    <p class="text-muted mb-4">
                        Invite your spouse or co-parent to share access to your registered children.
                        If they don't already have an account, we'll email them a secure link to set one up.
                    </p>

                    <form method="POST" action="{{ route('parent.co-parent.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    required
                                >
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    required
                                >
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                            >
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary">Send Invite</button>
                            <a href="{{ route('parent.dashboard') }}" class="small">Back to dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
