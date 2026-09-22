<x-mail::message>
# You're now a convenor

Hi {{ $person->first_name }},

You've been made convenor of **{{ $committee->name }}**.

Being convenor mostly means being the person others can contact, and setting out what the committee is
for. You can write those objectives, add members, and hand the role on to someone else whenever you like.

<x-mail::button :url="url('/committees/'.$committee->slug)">
Open the Committee
</x-mail::button>

@if (($person->public_name_mode ?? null) === 'anon_code')
<x-mail::panel>
You currently appear publicly as **{{ $person->public_display_name }}**, so that's what the committee page shows
as its convenor. There's a button on the committee page to show your name there instead — it changes nothing
about how you appear anywhere else.
</x-mail::panel>
@endif

If this isn't something you want, just reply and we'll pass it on to someone else &mdash; no hard feelings.

East Cork Reclaim Childhood
</x-mail::message>
