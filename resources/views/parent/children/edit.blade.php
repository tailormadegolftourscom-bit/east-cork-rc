@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h4 mb-3">Edit {{ $child->first_name }}'s Details</h1>

                    <form method="POST" action="{{ route('parent.children.update', $child) }}">
                        @csrf
                        @method('PUT')
                        @include('parent.children._fields', ['child' => $child])

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            @can('delete', $child)
                <div class="card border-danger shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h2 class="h6 text-danger mb-2">Remove {{ $child->first_name }}</h2>
                        <p class="text-muted small mb-3">
                            This permanently removes {{ $child->first_name }} from the site, along with their
                            school link and code name. This can't be undone. If your family has stopped taking
                            part — for example your child now has a smartphone — this is how you remove them.
                        </p>

                        <form method="POST" action="{{ route('parent.children.destroy', $child) }}" class="row g-2 align-items-end">
                            @csrf
                            @method('DELETE')
                            <div class="col-auto">
                                <label for="confirm_name" class="form-label small">
                                    Type <strong>{{ $child->first_name }}</strong> to confirm
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('confirm_name') is-invalid @enderror"
                                    id="confirm_name"
                                    name="confirm_name"
                                    autocomplete="off"
                                >
                                @error('confirm_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-outline-danger">Remove Child</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
