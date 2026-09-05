@extends('layouts.app')

@php($pageTitle = 'Stay Informed — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 mb-3">Stay informed</h1>
                        <p class="text-muted mb-4">
                            Get occasional updates as more schools, classes and local parent groups join across East
                            Cork. No spam, and you can unsubscribe at any time.
                        </p>

                        <form method="POST" action="{{ route('updates.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                >
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Get Updates</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
