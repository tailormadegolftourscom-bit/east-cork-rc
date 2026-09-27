@extends('layouts.app')

@php($pageTitle = 'Action Groups — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="h3 mb-3">Action Groups</h1>
                <p class="text-muted">
                    Groups are how things actually get organised — across East Cork, around an activity or
                    event, at school level or for a single class. Most are waiting for someone to start them off, and
                    that's much easier together than alone.
                </p>

                @if ($needingConvenor > 0)
                    <div class="alert alert-warning">
                        <strong>{{ $needingConvenor }}</strong>
                        {{ Str::plural('group', $needingConvenor) }}
                        {{ $needingConvenor === 1 ? 'has' : 'have' }} nobody running
                        {{ $needingConvenor === 1 ? 'it' : 'them' }} yet. If one covers your school or class,
                        you can step forward — it mostly means being the person others can contact.
                    </div>
                @endif
            </div>
        </div>

        @foreach ($types as $key => $label)
            @php($group = $byType[$key] ?? collect())
            @continue($group->isEmpty())

            <h2 class="h5 mt-4 mb-3">{{ $label }}{{ $key === 'regional' ? '' : ' groups' }}</h2>

            <div class="row g-3">
                @foreach ($group as $committee)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm {{ $committee->hasConvenor() ? '' : 'border-warning' }}">
                            <div class="card-body d-flex flex-column">
                                <h3 class="h6 mb-1">
                                    <a href="{{ route('committees.show', $committee) }}" class="text-decoration-none">
                                        {{ $committee->name }}
                                    </a>
                                </h3>

                                @if ($committee->school)
                                    <p class="small text-muted mb-2">
                                        {{ $committee->school->name }}{{ $committee->placeLabel() ? ' — '.$committee->placeLabel() : '' }}
                                    </p>
                                @endif

                                @php($convenor = $committee->members->firstWhere('role', 'convenor'))

                                <p class="small mb-3 flex-grow-1">
                                    @if ($convenor)
                                        <span class="text-muted">Convenor:</span>
                                        <strong>{{ $convenor->display_name }}</strong><br>
                                        <span class="text-muted">
                                            {{ $committee->members->count() }}
                                            {{ Str::plural('member', $committee->members->count()) }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning">Needs a convenor</span>
                                    @endif
                                </p>

                                <a href="{{ route('committees.show', $committee) }}"
                                   class="btn btn-sm {{ $convenor ? 'btn-outline-primary' : 'btn-warning' }}">
                                    {{ $convenor ? 'View Group' : 'Become Convenor' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>
@endsection
