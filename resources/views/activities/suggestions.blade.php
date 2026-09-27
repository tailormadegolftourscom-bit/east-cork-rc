@extends('layouts.app')

@php($pageTitle = $activity->title.' — Suggestions — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <p class="small mb-2"><a href="{{ route('activities') }}">&larr; What's On</a></p>

                <span class="badge {{ $activity->status === 'planned' ? 'text-bg-success' : 'text-bg-secondary' }} mb-2">
                    {{ $activity->statusLabel() }}
                </span>
                <h1 class="display-6 fw-bold mb-2">{{ $activity->title }}</h1>

                @if ($activity->whenLabel())
                    <p class="fs-5 fw-semibold mb-1">{{ $activity->whenLabel() }}</p>
                @endif
                @if ($activity->venue)
                    <p class="text-muted mb-1">{{ $activity->venue->label }}</p>
                @endif
                @if ($activity->summary)
                    <p class="text-muted mt-3">{{ $activity->summary }}</p>
                @endif

                <div class="card shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Suggestions so far</h2>

                        @if ($approved->isEmpty())
                            <p class="text-muted mb-0">None yet &mdash; be the first.</p>
                        @else
                            <ul class="mb-0">
                                @foreach ($approved as $suggestion)
                                    <li class="mb-1">{{ $suggestion->body }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Add a suggestion</h2>

                        @if ($canSuggest)
                            @if ($mine->isNotEmpty())
                                <div class="alert alert-light border small">
                                    <strong>Waiting for approval:</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach ($mine as $suggestion)
                                            <li>{{ $suggestion->body }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('activities.suggestions.store', $activity) }}">
                                @csrf
                                <label for="body" class="form-label">Your idea</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body"
                                          rows="3" maxlength="500" required>{{ old('body') }}</textarea>
                                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">
                                    One idea at a time works best. Suggestions appear once approved, and your name is
                                    never shown.
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Send Suggestion</button>
                            </form>
                        @else
                            <p class="text-muted mb-3">
                                Suggestions come from registered parents.
                                @guest
                                    Log in, or register as a parent, to add yours.
                                @endguest
                            </p>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Log In</a>
                                <a href="{{ route('register') }}" class="btn btn-primary">Register as a Parent</a>
                            @endguest
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
