@extends('layouts.app')

@php($pageTitle = $activity->title.' — East Cork Reclaim Childhood')

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
                    <p class="text-muted mb-1">
                        @if ($activity->venue->map_url)
                            <a href="{{ $activity->venue->map_url }}" target="_blank" rel="noopener">{{ $activity->venue->label }}</a>
                        @else
                            {{ $activity->venue->label }}
                        @endif
                    </p>
                @endif
                @if ($activity->convenor_name)
                    <p class="small mb-1">Convenor: {{ $activity->convenor_name }}</p>
                @endif
                @if ($activity->summary)
                    <p class="text-muted mt-3">{{ $activity->summary }}</p>
                @endif

                <div class="d-flex flex-wrap gap-3 small">
                    @if ($activity->link_url)
                        <a href="{{ $activity->link_url }}" target="_blank" rel="noopener">{{ $activity->link_label ?: 'More information' }}</a>
                    @endif
                    @if ($activity->suggestions_open)
                        <a href="{{ route('activities.suggestions', $activity) }}">Suggestions welcome &rarr;</a>
                    @endif
                </div>

                @if ($activity->isPast())
                    <div class="alert alert-light border mt-4 mb-0">This has already taken place.</div>
                @endif

                {{-- Signing children up --}}
                @if ($activity->signups_open && ! $activity->isPast())
                    <div class="card shadow-sm border-success mt-4">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-1">{{ $activity->isRecurring() ? 'Will your Rebel usually come?' : 'Sign up your Rebel' }}</h2>
                            <p class="small text-muted mb-3">
                                @if ($activity->isRecurring())
                                    One sign-up covers the regular sessions &mdash; it just lets the convenor know who to expect.
                                @else
                                    So the convenor knows how many to expect.
                                @endif
                                {{ $signupCount }} {{ Str::plural('Rebel', $signupCount) }} signed up so far.
                            </p>

                            @if ($isParent)
                                @if ($children->isEmpty())
                                    <p class="mb-2">Register your child first, then come back to sign them up.</p>
                                    <a href="{{ route('parent.children.create') }}" class="btn btn-success">Register Your Rebel</a>
                                @else
                                    <form method="POST" action="{{ route('activities.sign-up', $activity) }}">
                                        @csrf
                                        @foreach ($children as $child)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" name="children[]"
                                                       value="{{ $child->id }}" id="child-{{ $child->id }}"
                                                       @checked($signedUpIds->contains($child->id))>
                                                <label class="form-check-label" for="child-{{ $child->id }}">
                                                    {{ $child->first_name }}
                                                    <span class="text-muted small">({{ $child->public_label }})</span>
                                                </label>
                                            </div>
                                        @endforeach
                                        <button type="submit" class="btn btn-success mt-2">
                                            {{ $signedUpIds->isEmpty() ? 'Sign Up' : 'Update' }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="mb-3">Sign-up is for parents of registered Rebels.</p>
                                @guest
                                    <a href="{{ route('activities.log-in', $activity) }}" class="btn btn-outline-success me-2">Log In</a>
                                    <a href="{{ route('register') }}" class="btn btn-success">Register as a Parent</a>
                                @endguest
                            @endif
                        </div>
                    </div>
                @endif

                {{-- WhatsApp: only for people taking part --}}
                @if ($showWhatsapp)
                    <div class="card shadow-sm mt-4" style="border-color: #25d366;">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-2">WhatsApp group</h2>
                            <p class="small text-muted mb-3">
                                For updates on the day. Everyone in the group can see each other's phone numbers.
                                Please don't share the link.
                            </p>
                            <a href="{{ $activity->whatsapp_url }}" target="_blank" rel="noopener" class="btn text-white"
                               style="background: #25d366;">Join the WhatsApp Group</a>
                        </div>
                    </div>
                @endif

                {{-- Volunteering --}}
                @if ($activity->volunteers_open && ! $activity->isPast())
                    <div class="card shadow-sm border-warning mt-4" id="volunteer">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-1">Volunteer to help</h2>
                            @if ($activity->volunteer_note)
                                <p class="mb-3">{{ $activity->volunteer_note }}</p>
                            @endif

                            @if ($volunteered)
                                <p class="text-success mb-3">You've volunteered &mdash; thank you. You can update your details below.</p>
                            @endif

                            <form method="POST" action="{{ route('activities.volunteer', $activity) }}">
                                @csrf
                                <div class="d-none" aria-hidden="true">
                                    <label for="website">Leave this empty</label>
                                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                                </div>

                                <div class="row g-3">
                                    @unless ($isParent)
                                        <div class="col-md-6">
                                            <label for="vol_name" class="form-label">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="vol_name" name="name" value="{{ old('name') }}" required maxlength="150">
                                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="vol_email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="vol_email" name="email" value="{{ old('email') }}" required maxlength="150">
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    @endunless
                                    <div class="col-md-6">
                                        <label for="vol_phone" class="form-label">Mobile number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               id="vol_phone" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required maxlength="40">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="vol_note" class="form-label">Anything we should know? <span class="text-muted small">(optional)</span></label>
                                        <input type="text" class="form-control" id="vol_note" name="note" value="{{ old('note') }}" maxlength="500"
                                               placeholder="e.g. can only do the first hour">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning mt-3">{{ $volunteered ? 'Update' : 'Volunteer' }}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
