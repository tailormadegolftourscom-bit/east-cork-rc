@extends('layouts.app')

@php($pageTitle = $committee->name.' — East Cork Reclaim Childhood')
@php($iAmConvenor = $myMembership && $myMembership->isConvenor())
@php($viewer = auth()->user())
@php($needsConsent = $canAct && $viewer->public_name_mode === 'anon_code')

@section('content')
    <section class="py-5">
        <div class="row">
            <div class="col-lg-8">
                <nav class="small mb-2">
                    <a href="{{ route('committees.index') }}" class="text-muted">Committees</a>
                    @if ($committee->parentCommittee)
                        <span class="text-muted">/</span>
                        <a href="{{ route('committees.show', $committee->parentCommittee) }}" class="text-muted">
                            {{ $committee->parentCommittee->name }}
                        </a>
                    @endif
                </nav>

                <h1 class="h3 mb-1">{{ $committee->name }}</h1>
                <p class="text-muted">
                    <span class="badge text-bg-light border">{{ $committee->typeLabel() }}</span>
                    @if ($committee->school)
                        {{ $committee->school->name }}{{ $committee->schoolClass ? ' — '.$committee->schoolClass->display_name : '' }}
                    @endif
                </p>

                @if ($committee->objectives)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h6">What this committee is for</h2>
                            <p class="mb-0" style="white-space: pre-line">{{ $committee->objectives }}</p>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Who's on it</h2>

                        @if ($committee->isEmpty())
                            <p class="text-muted mb-0">
                                Nobody yet. This committee is waiting for someone to start it off.
                            </p>
                        @else
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Convenor</dt>
                                <dd class="col-sm-9">
                                    @if ($convenor)
                                        {{ $convenor->display_name }}
                                        <span class="text-muted small">
                                            since {{ $convenor->joined_at->format('F Y') }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning">Vacant</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Members</dt>
                                <dd class="col-sm-9">
                                    @forelse ($ordinaryMembers as $member)
                                        <div>
                                            {{ $member->display_name }}
                                            @if ($iAmConvenor)
                                                <form method="POST" class="d-inline"
                                                      action="{{ route('committees.members.remove', [$committee, $member]) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm text-danger p-0 ms-2">remove</button>
                                                </form>
                                                <form method="POST" class="d-inline"
                                                      action="{{ route('committees.hand-over', [$committee, $member]) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link btn-sm p-0 ms-2">make convenor</button>
                                                </form>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-muted">No other members yet.</span>
                                    @endforelse
                                </dd>
                            </dl>
                        @endif
                    </div>
                </div>

                @if ($committee->childCommittees->isNotEmpty())
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h6 mb-3">Committees under this one</h2>
                            <ul class="mb-0">
                                @foreach ($committee->childCommittees as $child)
                                    <li>
                                        <a href="{{ route('committees.show', $child) }}">{{ $child->name }}</a>
                                        @if ($child->members->isEmpty())
                                            <span class="badge text-bg-warning">Needs a convenor</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        @guest
                            <h2 class="h6">Want to get involved?</h2>
                            <p class="small text-muted">
                                Parents can join or convene a committee once they've registered.
                            </p>
                            <a href="{{ route('register') }}" class="btn btn-primary w-100 mb-2">Register as a Parent</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">Log In</a>
                        @endguest

                        @auth
                            @if (! $canAct)
                                <h2 class="h6">Getting involved</h2>
                                <p class="small text-muted mb-0">
                                    Committee places are taken up by parents. If you're a supporter rather than a
                                    parent, the convenor can add you — just ask them, or
                                    <a href="{{ route('supporters.create') }}">tell us how you'd like to help</a>.
                                </p>
                            @elseif ($myMembership)
                                <h2 class="h6">You're on this committee</h2>
                                <p class="small text-muted">
                                    You are the {{ $iAmConvenor ? 'convenor' : 'a member' }}.
                                </p>
                                <form method="POST" action="{{ route('committees.leave', $committee) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">Leave Committee</button>
                                </form>
                            @else
                                @php($vacant = ! $committee->hasConvenor())
                                <h2 class="h6">{{ $vacant ? 'Nobody is running this yet' : 'Join this committee' }}</h2>
                                <p class="small text-muted">
                                    {{ $vacant
                                        ? 'Becoming convenor mostly means being the person others can contact, and setting out what the committee is for.'
                                        : 'Members help out and are listed publicly on this page.' }}
                                </p>

                                <form method="POST"
                                      action="{{ $vacant ? route('committees.become-convenor', $committee) : route('committees.join', $committee) }}">
                                    @csrf

                                    @if ($needsConsent)
                                        <div class="alert alert-warning small">
                                            <strong>Your name will be shown publicly.</strong>
                                            You currently appear as <code>{{ $viewer->public_display_name }}</code>,
                                            but committee members are listed by name on this page.
                                            <div class="form-check mt-2">
                                                <input class="form-check-input @error('name_consent') is-invalid @enderror"
                                                       type="checkbox" name="name_consent" value="1" id="name_consent" required>
                                                <label class="form-check-label" for="name_consent">
                                                    I'm happy to be named here
                                                </label>
                                                @error('name_consent')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    <button type="submit" class="btn {{ $vacant ? 'btn-warning' : 'btn-primary' }} w-100">
                                        {{ $vacant ? 'Become Convenor' : 'Join Committee' }}
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                @if ($iAmConvenor)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h6">Convenor tools</h2>

                            <form method="POST" action="{{ route('committees.objectives', $committee) }}" class="mb-4">
                                @csrf @method('PUT')
                                <label for="objectives" class="form-label small">What is this committee for?</label>
                                <textarea name="objectives" id="objectives" rows="4"
                                          class="form-control form-control-sm">{{ old('objectives', $committee->objectives) }}</textarea>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Save Objectives</button>
                            </form>

                            <hr>

                            <form method="POST" action="{{ route('committees.members.add', $committee) }}">
                                @csrf
                                <label for="email" class="form-label small">Add a member by email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-sm mb-2"
                                       placeholder="their email address" required>
                                <div class="form-text small mb-2">
                                    Works for registered parents and for supporters. They'll get an email letting
                                    them know.
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Add Member</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
