<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>School Access</title>
</head>
<body>
<h1>School access has been prepared</h1>

<p>Hello,</p>

<p>
    A school user account has been prepared for <strong>{{ $school->name }}</strong>.
</p>

<p>
    You can use the password reset link sent separately to set your password and access the school area.
</p>

<p>
    Once logged in, you will be able to update class sizes and school contact details.
</p>

<p>
    School: {{ $school->name }}<br>
    Type: {{ ucfirst($school->school_type) }}<br>
    Town: {{ $school->town ?: '—' }}
</p>

<p>Thank you.</p>
</body>
</html>
