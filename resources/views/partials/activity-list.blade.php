{{-- Planned and suggested activities. Data comes from a view composer in
     AppServiceProvider, so any page can include this. Optional: $limit. --}}
@php($items = isset($limit) ? $listedActivities->take($limit) : $listedActivities)

@if ($items->isEmpty())
    <p class="text-muted">
        Nothing listed just now. Have an idea?
        <a href="mailto:info@eastcorkreclaimchildhood.ie?subject=Activity%20idea">Tell us</a>.
    </p>
@else
    <div class="row g-4">
        @foreach ($items as $activity)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm {{ $activity->status === 'planned' ? 'border-success' : '' }}">
                    <div class="card-body p-4">
                        <span class="badge {{ $activity->status === 'planned' ? 'text-bg-success' : 'text-bg-secondary' }} mb-2">
                            {{ $activity->statusLabel() }}
                        </span>
                        <h3 class="h5 mb-2">
                            <a href="{{ route('activities.show', $activity) }}" class="text-decoration-none">{{ $activity->title }}</a>
                        </h3>

                        @if ($activity->whenLabel())
                            <p class="fw-semibold small mb-1">{{ $activity->whenLabel() }}</p>
                        @endif

                        @if ($activity->venue)
                            <p class="small text-muted mb-2">
                                @if ($activity->venue->map_url)
                                    <a href="{{ $activity->venue->map_url }}" target="_blank" rel="noopener">{{ $activity->venue->label }}</a>
                                @else
                                    {{ $activity->venue->label }}
                                @endif
                            </p>
                        @endif

                        @if ($activity->summary)
                            <p class="small text-muted mb-2">{{ $activity->summary }}</p>
                        @endif

                        @if ($activity->convenor_name)
                            <p class="small mb-2">Convenor: {{ $activity->convenor_name }}</p>
                        @endif

                        @if ($activity->signups_open)
                            <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-success d-block mb-2">
                                Sign Up Your Rebel &rarr;
                            </a>
                        @endif

                        @if ($activity->volunteers_open)
                            <a href="{{ route('activities.show', $activity) }}#volunteer" class="btn btn-sm btn-outline-warning text-dark d-block mb-2">
                                Volunteers needed &rarr;
                            </a>
                        @endif

                        @if ($activity->suggestions_open)
                            <a href="{{ route('activities.suggestions', $activity) }}" class="btn btn-sm btn-warning d-block mb-2">
                                Suggestions welcome &rarr;
                            </a>
                        @endif

                        @if ($activity->link_url)
                            <a href="{{ $activity->link_url }}" target="_blank" rel="noopener" class="small">
                                {{ $activity->link_label ?: 'More information' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
