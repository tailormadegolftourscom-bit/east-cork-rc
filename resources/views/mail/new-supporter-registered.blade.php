<x-mail::message>
# New Supporter

**{{ $supporter->full_name }}** ({{ $supporter->email }}) has signed up as a supporter.

@if ($supporter->categories->isNotEmpty())
**Offering:** {{ $supporter->categories->pluck('name')->join(', ') }}
@endif

@if ($supporter->phone)
**Phone:** {{ $supporter->phone }} (prefers {{ $supporter->preferred_contact_method }})
@endif

@if ($supporter->message)
<x-mail::panel>
{{ $supporter->message }}
</x-mail::panel>
@endif

<x-mail::button :url="url('/admin/supporters')">
View Supporters
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
