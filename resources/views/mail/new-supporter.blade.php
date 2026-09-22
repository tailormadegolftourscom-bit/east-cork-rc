<x-mail::message>
# New Parent Registered

**{{ $parent->first_name }} {{ $parent->last_name }}** ({{ $parent->email }}) has just completed registration and
is now counted as a supporter.

@php($coParented = $parent->guardianOfChildren)

@if ($coParented->isNotEmpty())
<x-mail::panel>
Already a co-parent of {{ $coParented->pluck('first_name')->join(', ', ' and ') }}, registered by
{{ optional($coParented->first()->owner)->full_name ?? 'another parent' }}. Nothing more is needed from them —
they will not be chased to add children, and those children are already on their dashboard.
</x-mail::panel>
@elseif ($parent->children()->exists())
<x-mail::panel>
{{ $parent->children()->count() }} {{ Str::plural('child', $parent->children()->count()) }} already registered.
</x-mail::panel>
@else
<x-mail::panel>
No children added yet. You'll get a follow-up note if that's still true in a few days.
</x-mail::panel>
@endif

<x-mail::button :url="url('/admin/parents')">
View Parents
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
