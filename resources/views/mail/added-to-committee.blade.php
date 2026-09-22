<x-mail::message>
# You've been added to a committee

Hi {{ $person->first_name }},

The convenor of **{{ $committee->name }}** has added you as a member.

@if ($committee->objectives)
<x-mail::panel>
{{ $committee->objectives }}
</x-mail::panel>
@endif

Committee members are listed publicly on the committee page. You are shown as
**{{ $person->public_display_name ?? $person->full_name }}**@if (($person->public_name_mode ?? null) === 'anon_code'), because you've chosen to appear anonymously. There's a button on the committee page if you'd rather be named there@endif.

<x-mail::button :url="url('/committees/'.$committee->slug)">
See the Committee
</x-mail::button>

East Cork Reclaim Childhood
</x-mail::message>
