<x-mail::message>
# New Parent Registered

**{{ $person->first_name }} {{ $person->last_name }}** ({{ $person->email }}) has just completed registration and
is now a supporter.

<x-mail::panel>
No children added yet. You'll get a follow-up note if that's still true in a few days.
</x-mail::panel>

<x-mail::button :url="url('/admin/supporters')">
View Supporters
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
