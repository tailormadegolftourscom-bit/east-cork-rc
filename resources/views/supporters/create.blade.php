@extends('layouts.app')

@php($pageTitle = 'Support the Initiative — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="h3 mb-3">Support the initiative</h1>
                <p class="text-muted mb-4">
                    You don't need a child in a local school to help. Whether you just want to be counted, have
                    ideas to share, or can lend a hand when kids get together, it all makes a difference — and
                    it's much easier together than alone.
                </p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <form method="POST" action="{{ route('supporters.store') }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        Phone <span class="text-muted small">(optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="preferred_contact_method" class="form-label">How should we contact you?</label>
                                    <select class="form-select" id="preferred_contact_method" name="preferred_contact_method">
                                        <option value="email" @selected(old('preferred_contact_method', 'email') === 'email')>Email</option>
                                        <option value="sms" @selected(old('preferred_contact_method') === 'sms')>Regular Text</option>
                                        <option value="whatsapp" @selected(old('preferred_contact_method') === 'whatsapp')>WhatsApp</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <fieldset>
                                <legend class="form-label h6">How would you like to help?</legend>
                                <p class="text-muted small mb-3">Choose as many as apply.</p>

                                @error('categories')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror

                                @foreach ($categories as $category)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="categories[]"
                                               value="{{ $category->id }}" id="category-{{ $category->id }}"
                                               @checked(in_array($category->id, old('categories', []))) >
                                        <label class="form-check-label" for="category-{{ $category->id }}">
                                            <strong>{{ $category->name }}</strong>
                                            @if ($category->description)
                                                <span class="d-block text-muted small">{{ $category->description }}</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </fieldset>

                            <div class="mt-4">
                                <label for="message" class="form-label">
                                    Anything you'd like to tell us? <span class="text-muted small">(optional)</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message" name="message" rows="4">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Count Me In</button>
                        </form>
                    </div>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    Are you a parent with a child in a local school?
                    <a href="{{ route('register') }}">Register as a parent instead</a> — you'll be able to add your
                    children and see your school's progress.
                </p>
            </div>
        </div>
    </section>
@endsection
