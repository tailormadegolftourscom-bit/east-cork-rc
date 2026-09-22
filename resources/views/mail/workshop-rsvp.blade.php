<x-mail::message>
# Thanks {{ $rsvp->name }} — you're down for it

We have you for the workshop on **{{ $rsvp->label() }}** at **{{ config('notice.venue') }}**.

@if ($rsvp->party_size > 1)
You told us {{ $rsvp->adults }} {{ Str::plural('adult', $rsvp->adults) }}@if ($rsvp->children) and {{ $rsvp->children }} {{ Str::plural('child', $rsvp->children) }}@endif.
@endif

These evenings are informal. Nothing is decided yet, and the point is to hear what parents actually want —
so come with your own ideas rather than expecting a finished plan.

@if ($rsvp->note)
<x-mail::panel>
You added: {{ $rsvp->note }}
</x-mail::panel>
@endif

If your plans change, just reply to this email and let us know.

East Cork Reclaim Childhood
</x-mail::message>
