@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h4 mb-3">Register a Child</h1>

                    @if (session('duplicate_child'))
                        @php($dupe = session('duplicate_child'))
                        <div class="alert alert-warning">
                            <h2 class="h6">Is this the same child?</h2>
                            <p class="mb-2">
                                <strong>{{ $dupe['name'] }}</strong> is already registered
                                @if ($dupe['owner']) by {{ $dupe['owner'] }} @endif
                                and you're listed as a co-parent, so they're already on your dashboard and
                                already counted. Adding them again would register the same child twice.
                            </p>
                            <p class="mb-2 small">
                                If this is a different child who happens to share the name, tick below and
                                continue &mdash; your details have been kept.
                            </p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirm_not_duplicate"
                                       value="1" id="confirm_not_duplicate" form="child-form">
                                <label class="form-check-label" for="confirm_not_duplicate">
                                    This is a different child
                                </label>
                            </div>
                        </div>
                    @elseif (! empty($alreadyCoParented) && $alreadyCoParented->isNotEmpty())
                        <div class="alert alert-info">
                            You're already a co-parent of
                            <strong>{{ $alreadyCoParented->pluck('first_name')->join(', ', ' and ') }}</strong>@if (optional($alreadyCoParented->first()->owner)->full_name), registered by {{ $alreadyCoParented->first()->owner->full_name }}@endif.
                            They're on your dashboard already and counted &mdash; there's no need to add them
                            again. Only add a child who isn't there yet.
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Please use your child's real first name. It is never shown publicly &mdash;
                        only used so we can track momentum by school and class.
                    </p>

                    <form method="POST" action="{{ route('parent.children.store') }}" id="child-form">
                        @csrf
                        @include('parent.children._fields', ['child' => null])

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Child</button>
                            <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
