<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'East Cork Reclaim Childhood' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @production
        @unless (request()->is('admin', 'admin/*'))
        {{-- Privacy-friendly analytics by Plausible: no cookies and no personal
             data, so it needs no consent banner. Production only, so local
             testing does not land in the numbers, and never on admin pages —
             running the site is not traffic, and those paths carry record ids
             that have no business in a third-party service. --}}
        <script defer src="https://plausible.io/js/pa-5dEdyJ56m7OcEUiJlFroz.js"></script>
        <script>
            window.plausible = window.plausible || function () { (plausible.q = plausible.q || []).push(arguments) };
            plausible.init = plausible.init || function (i) { plausible.o = i || {} };
            plausible.init()
        </script>
        @endunless
    @endproduction
</head>
<body>
<nav class="navbar navbar-expand-xl navbar-dark site-navbar">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand fw-bold">East Cork Reclaim Childhood</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-xl-0 text-nowrap small">
                <li class="nav-item"><a href="{{ route('activities') }}" class="nav-link px-xl-1 fw-bold text-warning">What's On</a></li>
                <li class="nav-item"><a href="{{ route('for-kids') }}" class="nav-link px-xl-1">For Kids</a></li>
                <li class="nav-item"><a href="{{ route('parents') }}" class="nav-link px-xl-1">Parents</a></li>
                <li class="nav-item"><a href="{{ route('schools.index') }}" class="nav-link px-xl-1">Schools</a></li>
                <li class="nav-item"><a href="{{ route('committees.index') }}" class="nav-link px-xl-1">Action Groups</a></li>
                <li class="nav-item"><a href="{{ route('resources') }}" class="nav-link px-xl-1">Resources</a></li>
                <li class="nav-item"><a href="{{ route('faqs') }}" class="nav-link px-xl-1">FAQs</a></li>
                <li class="nav-item"><a href="{{ route('about') }}" class="nav-link px-xl-1">About</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2 text-nowrap">
                @auth
                    @php($user = auth()->user())

                    @if ((int) $user->is_admin === 1 || $user->user_type === 'admin')
                        <a href="/admin" class="btn btn-outline-light btn-sm">Admin</a>
                    @elseif ($user->user_type === 'school')
                        <a href="{{ route('school.dashboard') }}" class="btn btn-outline-light btn-sm">My School</a>
                    @elseif ($user->user_type === 'parent')
                        <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-light btn-sm">My Account</a>
                    @endif

                    <form method="POST" action="/logout" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="{{ route('supporters.create') }}" class="btn btn-outline-light btn-sm">Join as a Supporter</a>
                    <a href="{{ route('register') }}" class="btn btn-brand-accent btn-sm">Join as a Parent</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer class="site-footer py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h2 class="h6 text-uppercase text-white-50">East Cork Reclaim Childhood</h2>
                <p class="small text-white-50 mb-0">
                    A parent-led pilot helping East Cork families hold off on social media together, and reclaim
                    real childhood in the meantime.
                </p>
            </div>
            <div class="col-md-4">
                <h2 class="h6 text-uppercase text-white-50">Explore</h2>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('parents') }}" class="footer-link">For Parents</a></li>
                    <li><a href="{{ route('schools.index') }}" class="footer-link">Schools</a></li>
                    <li><a href="{{ route('committees.index') }}" class="footer-link">Action Groups</a></li>
                    <li><a href="{{ route('the-issue') }}" class="footer-link">The Issue</a></li>
                    <li><a href="{{ route('school-registration.create') }}" class="footer-link">Add My School</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h2 class="h6 text-uppercase text-white-50">Stay in Touch</h2>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('supporters.create') }}" class="footer-link">Join as a Supporter</a></li>
                    <li><a href="mailto:info@eastcorkreclaimchildhood.ie" class="footer-link">info@eastcorkreclaimchildhood.ie</a></li>
                    <li><a href="{{ route('privacy') }}" class="footer-link">Privacy &amp; Data Protection</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <p class="small text-white-50 mb-0">&copy; {{ now()->year }} East Cork Reclaim Childhood.</p>
    </div>
</footer>
    @include('partials.first-visit-notice')
</body>
</html>
