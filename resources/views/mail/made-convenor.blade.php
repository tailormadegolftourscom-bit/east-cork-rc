<x-mail::message>
# You're now a convenor

Hi {{ $person->first_name }},

You've been made convenor of **{{ $committee->name }}**.

Being convenor mostly means being the person others can contact, and setting out what the committee is
for. You can write those objectives, add members, and hand the role on to someone else whenever you like.

<x-mail::button :url="url('/committees/'.$committee->slug)">
Open the Committee
</x-mail::button>

If this isn't something you want, just reply and we'll pass it on to someone else &mdash; no hard feelings.

East Cork Reclaim Childhood
</x-mail::message>
