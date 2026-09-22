<x-mail::message>
# Almost there, {{ $parent->first_name }}

You were added to East Cork Reclaim Childhood {{ $daysWaiting }} days ago, but the account hasn't been set up
yet — it still needs a password before you can sign in.

We've sent a fresh link along with this email. Once you've set a password you'll be able to see your children,
your school's progress, and keep your own details up to date.

<x-mail::button :url="url('/login')">
Set My Password
</x-mail::button>

@if ($reminderNumber >= 2)
<x-mail::panel>
If we don't hear from you in the next few days we'll remove the part-finished account, just to keep things
tidy. Nothing is lost — you can always sign up again later.
</x-mail::panel>
@endif

If this wasn't meant for you, you can ignore this email and the account will disappear on its own.

East Cork Reclaim Childhood
</x-mail::message>
