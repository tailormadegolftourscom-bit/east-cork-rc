<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your school has been added</title>
</head>
<body>
<h1>Your school has been added</h1>

<p>Hello {{ $registrationRequest->contact_name }},</p>

<p>
    Thank you for your submission. We have now added
    <strong>{{ $school->name }}</strong>
    to East Cork Reclaim Childhood.
</p>

<p>
    This is a parent-led initiative. Schools are welcome to support it, but there is no obligation on any school to take part.
</p>

<p>
    If your school chooses to support the initiative, we can later provide secure school-user access so class sizes and school contact details can be updated directly.
</p>

<p>
    School type: {{ ucfirst($school->school_type) }}<br>
    Town: {{ $school->town ?: '—' }}
</p>

<p>Thank you.</p>
</body>
</html>
