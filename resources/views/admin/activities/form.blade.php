@extends('layouts.app')

@section('content')
    @php($editing = $activity->exists)

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $editing ? 'Edit '.$activity->title : 'Add Activity' }}</h1>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ $editing ? route('admin.activities.update', $activity) : route('admin.activities.store') }}">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required maxlength="150"
                               value="{{ old('title', $activity->title) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach (\App\Models\Activity::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $activity->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Planned: it's happening. Suggested: an idea looking for someone to run it.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="venue_id" class="form-label">Venue</label>
                        <select class="form-select" id="venue_id" name="venue_id">
                            <option value="">No venue yet</option>
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}" @selected((string) old('venue_id', $activity->venue_id) === (string) $venue->id)>
                                    {{ $venue->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="convenor_name" class="form-label">Convenor <span class="text-muted small">(shown publicly)</span></label>
                        <input type="text" class="form-control" id="convenor_name" name="convenor_name" maxlength="150"
                               value="{{ old('convenor_name', $activity->convenor_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="starts_on" class="form-label">Date <span class="text-muted small">(one-offs only)</span></label>
                        <input type="date" class="form-control" id="starts_on" name="starts_on"
                               value="{{ old('starts_on', $activity->starts_on?->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-8">
                        <label for="schedule" class="form-label">When, in words</label>
                        <input type="text" class="form-control" id="schedule" name="schedule" maxlength="150"
                               placeholder="Every Monday, 3–5pm"
                               value="{{ old('schedule', $activity->schedule) }}">
                        <div class="form-text">For a weekly session leave the date empty and say it here. For a one-off, the time and meeting point.</div>
                    </div>

                    <div class="col-12">
                        <label for="summary" class="form-label">Description</label>
                        <textarea class="form-control" id="summary" name="summary" rows="3" maxlength="2000">{{ old('summary', $activity->summary) }}</textarea>
                    </div>

                    <div class="col-md-8">
                        <label for="link_url" class="form-label">Link <span class="text-muted small">(optional)</span></label>
                        <input type="url" class="form-control" id="link_url" name="link_url" maxlength="255"
                               value="{{ old('link_url', $activity->link_url) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="link_label" class="form-label">Link text</label>
                        <input type="text" class="form-control" id="link_label" name="link_label" maxlength="100"
                               value="{{ old('link_label', $activity->link_label) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="sort_order" class="form-label">Sort order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                               value="{{ old('sort_order', $activity->sort_order ?? 0) }}">
                    </div>

                    <div class="col-md-8 d-flex align-items-end">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $activity->is_active))>
                            <label class="form-check-label" for="is_active">Show on the website</label>
                        </div>
                        <div class="form-check ms-4">
                            <input type="hidden" name="suggestions_open" value="0">
                            <input class="form-check-input" type="checkbox" id="suggestions_open" name="suggestions_open" value="1"
                                   @checked(old('suggestions_open', $activity->suggestions_open))>
                            <label class="form-check-label" for="suggestions_open">Suggestions welcome</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">{{ $editing ? 'Save' : 'Add Activity' }}</button>
            </form>
        </div>
    </div>
@endsection
