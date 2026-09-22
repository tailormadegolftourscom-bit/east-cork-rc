<x-mail::message>
# Your co-parent invite expired

Hi {{ $inviter->first_name }},

You invited **{{ $removed->first_name }} {{ $removed->last_name }}** ({{ $removed->email }}) as a co-parent
{{ $daysWaiting }} days ago, but they never set a password, so we've removed the part-finished account.

Your children and your own account are unaffected — only the unused invite has gone.

<x-mail::button :url="url('/parent/co-parent/invite')">
Invite Them Again
</x-mail::button>

If the email address was wrong, this is a good moment to check it before sending another invite.

East Cork Reclaim Childhood
</x-mail::message>
