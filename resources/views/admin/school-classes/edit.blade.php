@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Edit Class</h1>
                    <p class="text-muted mb-0">{{ $school->name }}</p>
                </div>

                <a href="{{ route('admin.schools.classes.index', $school) }}" class="btn btn-outline-secondary">
                    Back to Classes
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.schools.classes.update', [$school, $schoolClass]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="class_level" class="form-label">Class Level</label>
                            <select name="class_level" id="class_level" class="form-select" required>
                                @foreach ($classLevelLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('class_level', $schoolClass->class_level) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="class_stream" class="form-label">Class Stream</label>
                            <input
                                type="text"
                                name="class_stream"
                                id="class_stream"
                                class="form-control"
                                value="{{ old('class_stream', $schoolClass->class_stream) }}"
                                placeholder="A, B, C"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="total_pupils" class="form-label">Total Pupils</label>
                            <input
                                type="number"
                                min="0"
                                name="total_pupils"
                                id="total_pupils"
                                class="form-control"
                                value="{{ old('total_pupils', $schoolClass->total_pupils) }}"
                            >
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $schoolClass->is_active))>
                            <label class="form-check-label" for="is_active">
                                Class is active
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Class</button>
                            <a href="{{ route('admin.schools.classes.index', $school) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
