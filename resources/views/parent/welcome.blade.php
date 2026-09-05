@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h3 mb-3">Welcome</h1>

                    <p class="lead text-muted">
                        Thank you for completing your details.
                    </p>

                    <p>
                        Your supporter profile is now set up. The next step is to register your child or children,
                        but you can also do that later.
                    </p>

                    <dl class="row mb-4">
                        <dt class="col-sm-4">Support Status</dt>
                        <dd class="col-sm-8">
                            {{ ucfirst(optional($supporter)->support_status ?? 'supporting') }}
                        </dd>

                        <dt class="col-sm-4">Preferred Contact</dt>
                        <dd class="col-sm-8">
                            {{ ucfirst($person->preferred_contact_method ?? 'email') }}
                        </dd>

                        <dt class="col-sm-4">Public Name</dt>
                        <dd class="col-sm-8">
                            {{ $person->public_display_name }}
                        </dd>
                    </dl>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="/parent/children/create" class="btn btn-primary">
                            Register a Child
                        </a>

                        <a href="/" class="btn btn-outline-secondary">
                            Do This Later
                        </a>
                    </div>

                    <hr class="my-4">

                    <p class="mb-0 text-muted">
                        You can return later to add child details, update your contact preferences, or change how your name appears publicly.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
