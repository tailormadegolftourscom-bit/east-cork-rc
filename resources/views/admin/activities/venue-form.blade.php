@extends('layouts.app')

@section('content')
    @php($editing = $venue->exists)

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $editing ? 'Edit '.$venue->name : 'Add Venue' }}</h1>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ $editing ? route('admin.venues.update', $venue) : route('admin.venues.store') }}">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="150"
                               value="{{ old('name', $venue->name) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="town" class="form-label">Town</label>
                        <input type="text" class="form-control" id="town" name="town" maxlength="100"
                               value="{{ old('town', $venue->town) }}">
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Notes <span class="text-muted small">(optional)</span></label>
                        <input type="text" class="form-control" id="description" name="description" maxlength="500"
                               value="{{ old('description', $venue->description) }}">
                    </div>

                    <div class="col-12">
                        <label for="website_url" class="form-label">Website <span class="text-muted small">(optional)</span></label>
                        <input type="url" class="form-control" id="website_url" name="website_url" maxlength="255"
                               value="{{ old('website_url', $venue->website_url) }}">
                        <div class="form-text">Shown with the notes under Local resources on the Resources page.</div>
                    </div>

                    <div class="col-md-8">
                        <label for="map_url" class="form-label">Map link <span class="text-muted small">(optional)</span></label>
                        <input type="url" class="form-control" id="map_url" name="map_url" maxlength="255"
                               value="{{ old('map_url', $venue->map_url) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="sort_order" class="form-label">Sort order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                               value="{{ old('sort_order', $venue->sort_order ?? 0) }}">
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $venue->is_active))>
                            <label class="form-check-label" for="is_active">Show on the website</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">{{ $editing ? 'Save' : 'Add Venue' }}</button>
            </form>
        </div>
    </div>
@endsection
