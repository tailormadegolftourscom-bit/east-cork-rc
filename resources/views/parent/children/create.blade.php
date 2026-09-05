@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h4 mb-3">Register a Child</h1>

                    <p class="text-muted mb-4">
                        Please use your child's real first name. It is never shown publicly &mdash;
                        only used so we can track momentum by school and class.
                    </p>

                    <form method="POST" action="{{ route('parent.children.store') }}" id="child-form">
                        @csrf
                        @include('parent.children._fields', ['child' => null])

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Child</button>
                            <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
