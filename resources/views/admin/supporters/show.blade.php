@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $supporter->full_name }}</h1>
        <a href="{{ route('admin.supporters.index') }}" class="btn btn-outline-secondary btn-sm">Back to Supporters</a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6">Contact</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $supporter->email }}</dd>

                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $supporter->phone ?: '—' }}</dd>

                        <dt class="col-sm-4">Preferred Contact</dt>
                        <dd class="col-sm-8">{{ ucfirst($supporter->preferred_contact_method) }}</dd>

                        <dt class="col-sm-4">Joined</dt>
                        <dd class="col-sm-8">{{ optional($supporter->joined_at)->format('Y-m-d') }}</dd>

                        <dt class="col-sm-4">Counted</dt>
                        <dd class="col-sm-8">{{ $supporter->is_active ? 'Yes' : 'No' }}</dd>
                    </dl>
                </div>
            </div>

            @if ($supporter->message)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h6">What they told us</h2>
                        <p class="mb-0">{{ $supporter->message }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 mb-3">Offering</h2>
                    <form method="POST" action="{{ route('admin.supporters.categories', $supporter) }}">
                        @csrf
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                       value="{{ $category->id }}" id="scat-{{ $category->id }}"
                                       @checked($supporter->categories->contains($category->id))>
                                <label class="form-check-label" for="scat-{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-sm btn-primary mt-3">Save</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <h2 class="h6 text-danger">Danger Zone</h2>

                    @if ($supporter->is_active)
                        <p class="small text-muted">
                            Deactivating keeps their details but stops counting them publicly. Reversible.
                        </p>
                        <form method="POST" action="{{ route('admin.supporters.deactivate', $supporter) }}" class="mb-3">
                            @csrf
                            <input type="text" name="reason" class="form-control form-control-sm mb-2"
                                   placeholder="Reason" required>
                            <button type="submit" class="btn btn-sm btn-outline-warning">Deactivate</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.supporters.reactivate', $supporter) }}" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Reactivate</button>
                        </form>
                    @endif

                    <hr>

                    <p class="small text-muted">Deleting removes the record entirely. It cannot be undone.</p>
                    <form method="POST" action="{{ route('admin.supporters.destroy', $supporter) }}">
                        @csrf @method('DELETE')
                        <input type="text" name="reason" class="form-control form-control-sm mb-2"
                               placeholder="Reason for deleting" required>
                        <input type="text" name="confirm_email" class="form-control form-control-sm mb-2"
                               placeholder="Type {{ $supporter->email }} to confirm" required>
                        <button type="submit" class="btn btn-sm btn-danger">Delete Supporter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
