<x-mail::message>
# Still No Child Added

**{{ $person->first_name }} {{ $person->last_name }}** ({{ $person->email }}) registered as a supporter
{{ $daysSinceJoined }} days ago and still hasn't added a child.

Might be worth a quick check-in, or they simply haven't gotten to it yet — this is a one-off note, you won't
get repeated reminders for the same person.

<x-mail::button :url="url('/admin/supporters?no_children=1')">
View Supporters Without Children
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
