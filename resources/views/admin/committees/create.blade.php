@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Add Committee</h1>
        <a href="{{ route('admin.committees.index') }}" class="btn btn-outline-secondary btn-sm">Back to Committees</a>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.committees.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="committee_type" class="form-label">Level</label>
                            <select name="committee_type" id="committee_type" class="form-select" required>
                                @foreach ($types as $key => $label)
                                    <option value="{{ $key }}" @selected(old('committee_type') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Activity committees are the free-form ones — a Ladysbridge activity committee, say.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug') }}" required>
                            <div class="form-text">Lowercase letters, numbers and hyphens. Used in the public URL.</div>
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="school_id" class="form-label">School <span class="text-muted small">(school committees)</span></label>
                            <select name="school_id" id="school_id" class="form-select @error('school_id') is-invalid @enderror">
                                <option value="">— none —</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id') == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="school_class_id" class="form-label">Class <span class="text-muted small">(class committees)</span></label>
                            <select name="school_class_id" id="school_class_id" class="form-select @error('school_class_id') is-invalid @enderror">
                                <option value="">— none —</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('school_class_id') == $class->id)>
                                        {{ optional($class->school)->name }} — {{ $class->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Only classes without a committee are listed.</div>
                            @error('school_class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="town" class="form-label">Town <span class="text-muted small">(optional)</span></label>
                            <input type="text" name="town" id="town" class="form-control" value="{{ old('town') }}">
                        </div>

                        <div class="mb-3">
                            <label for="objectives" class="form-label">Objectives <span class="text-muted small">(optional)</span></label>
                            <textarea name="objectives" id="objectives" rows="4" class="form-control">{{ old('objectives') }}</textarea>
                            <div class="form-text">The convenor can change this later.</div>
                        </div>

                        <div class="mb-3">
                            <label for="parent_committee_id" class="form-label">Sits under <span class="text-muted small">(optional)</span></label>
                            <select name="parent_committee_id" id="parent_committee_id" class="form-select">
                                <option value="">— work it out automatically —</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}" @selected(old('parent_committee_id') == $parent->id)>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Left blank, a class committee sits under its school's, and school and activity
                                committees under the East Cork one.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Committee</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6">What happens next</h2>
                    <p class="small text-muted mb-0">
                        The committee appears publicly straight away with a <strong>Become Convenor</strong> button.
                        Any registered parent can step forward; once someone has, the button becomes
                        <strong>Join Committee</strong> for everyone else. The convenor can then set the objectives
                        and add supporters who don't have logins.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
