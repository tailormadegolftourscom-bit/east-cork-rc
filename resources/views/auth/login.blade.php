<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/login">
    @csrf

    <div>
        <label for="email">Email</label><br>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>

    <div style="margin-top: 12px;">
        <label for="password">Password</label><br>
        <input id="password" type="password" name="password" required>
    </div>

    <div style="margin-top: 12px;">
        <label>
            <input type="checkbox" name="remember">
            Remember me
        </label>
    </div>

    <div style="margin-top: 16px;">
        <button type="submit">Sign in</button>
    </div>
</form>
</body>
</html>
