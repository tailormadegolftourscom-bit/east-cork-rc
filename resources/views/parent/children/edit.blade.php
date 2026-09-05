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
        </div>
    </div>
@endsection
