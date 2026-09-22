<x-mail::message>
@if ($rsvp->isAlternative())
# Thanks {{ $rsvp->name }} — noted

Neither the {{ collect(config('notice.workshops'))->pluck('label')->join(' nor the ') }} evening suits you.

<x-mail::panel>
You told us: {{ $rsvp->note }}
</x-mail::panel>

If enough people say the same we will arrange another evening and let you know. In the meantime, nothing
is decided — you are not missing a vote.
@else
# Thanks {{ $rsvp->name }} — you're down for it

We have you for the workshop on **{{ $rsvp->label() }}** at **{{ config('notice.venue') }}**.

@if ($rsvp->attendees > 1)
You told us {{ $rsvp->attendees }} of you are coming.
@endif

These evenings are informal. Nothing is decided yet, and the point is to hear what parents actually want —
so come with your own ideas rather than expecting a finished plan.

@if ($rsvp->note)
<x-mail::panel>
You added: {{ $rsvp->note }}
</x-mail::panel>
@endif
@endif

If your plans change, just reply to this email and let us know.

East Cork Reclaim Childhood
</x-mail::message>
