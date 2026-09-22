<x-mail::message>
# New Parent Registered

**{{ $parent->first_name }} {{ $parent->last_name }}** ({{ $parent->email }}) has just completed registration and
is now counted as a supporter.

<x-mail::panel>
No children added yet. You'll get a follow-up note if that's still true in a few days.
</x-mail::panel>

<x-mail::button :url="url('/admin/parents')">
View Parents
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
