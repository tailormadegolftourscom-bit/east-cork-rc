<x-mail::message>
# You've been added to a group

Hi {{ $person->first_name }},

The convenor of **{{ $committee->name }}** has added you as a member.

@if ($committee->objectives)
<x-mail::panel>
{{ $committee->objectives }}
</x-mail::panel>
@endif

Group members are listed publicly on the group page. You are shown as
**{{ $person->public_display_name ?? $person->full_name }}**@if (($person->public_name_mode ?? null) === 'anon_code'), because you've chosen to appear anonymously. There's a button on the committee page if you'd rather be named there@endif.

<x-mail::button :url="route('committees.show', $committee)">
See the Group
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
