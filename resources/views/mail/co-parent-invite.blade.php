<x-mail::message>
# You've Been Added as a Co-Parent

Hello,

**{{ $inviter->public_display_name }}** has added you as a co-parent on East Cork Reclaim Childhood.
You'll now share access to the same registered children on the account.

@if($isNewAccount)
You'll receive a separate email shortly with a secure link to set a password and log in.
@else
You can log in with your existing account to see them on your dashboard.
@endif

<x-mail::button :url="url('/login')">
Log In
</x-mail::button>

This is a parent-led initiative — you're welcome to take part, but nothing here is required.

Thanks,<br>
East Cork Reclaim Childhood
</x-mail::message>
