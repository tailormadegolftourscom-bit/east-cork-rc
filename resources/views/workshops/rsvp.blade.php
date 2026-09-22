@extends('layouts.app')

@php($pageTitle = 'RSVP for a workshop — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h1 class="h3 mb-3">RSVP for a workshop</h1>

                <p class="text-muted">
                    Two informal evenings at <strong>{{ $venue }}</strong> to talk about what East Cork Reclaim
                    Childhood should actually be. Nothing is decided yet — the point is to hear what parents want.
                    Come to either one.
                </p>

                <p class="text-muted small">
                    No account needed. We only ask for numbers so we know how many chairs to put out.
                </p>

                <div class="card shadow-sm mt-4">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('workshops.rsvp.store') }}">
                            @csrf

                            <fieldset class="mb-4">
                                <legend class="form-label h6">Which evening suits you?</legend>
                                @foreach ($workshops as $key => $workshop)
                                    <div class="form-check">
                                        <input class="form-check-input @error('workshop') is-invalid @enderror"
                                               type="radio" name="workshop" value="{{ $key }}"
                                               id="workshop-{{ $key }}"
                                               @checked(old('workshop', $selected) === $key) required>
                                        <label class="form-check-label" for="workshop-{{ $key }}">
                                            <strong>{{ $workshop['label'] }}</strong>
                                        </label>
                                    </div>
                                @endforeach
                                @error('workshop')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </fieldset>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        Phone <span class="text-muted small">(optional)</span>
                                    </label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                           value="{{ old('phone') }}">
                                </div>

                                <div class="col-6 col-md-3">
                                    <label for="adults" class="form-label">Adults</label>
                                    <input type="number" name="adults" id="adults" min="1" max="20"
                                           class="form-control @error('adults') is-invalid @enderror"
                                           value="{{ old('adults', 1) }}" required>
                                    @error('adults')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6 col-md-3">
                                    <label for="children" class="form-label">Children</label>
                                    <input type="number" name="children" id="children" min="0" max="20"
                                           class="form-control @error('children') is-invalid @enderror"
                                           value="{{ old('children', 0) }}" required>
                                    @error('children')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="note" class="form-label">
                                    Anything you'd like to raise on the night?
                                    <span class="text-muted small">(optional)</span>
                                </label>
                                <textarea name="note" id="note" rows="3" class="form-control">{{ old('note') }}</textarea>
                                <div class="form-text">
                                    Ideas for activities are especially welcome — that's most of what the evening is for.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Count Me In</button>
                        </form>
                    </div>
                </div>

                <p class="text-muted small mt-3">
                    Can't make either evening? You can still
                    <a href="{{ route('supporters.create') }}">share an idea</a> or
                    <a href="{{ route('register') }}">register your children</a>.
                </p>
            </div>
        </div>
    </section>
@endsection
