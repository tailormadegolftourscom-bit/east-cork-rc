<x-mail::message>
# Your School Has Been Added

Hello {{ $registrationRequest->contact_name }},

Thank you for your submission. We've added **{{ $school->name }}** to East Cork Reclaim Childhood.

<x-mail::panel>
**{{ $school->name }}**<br>
{{ ucfirst($school->school_type) }} school{{ $school->town ? ' · ' . $school->town : '' }}
</x-mail::panel>

This is a parent-led initiative. Schools are welcome to support it, but there is no obligation on any
school to take part.

If your school chooses to support the initiative, we can provide secure school-user access so class
sizes and contact details can be kept up to date.

Thanks,<br>
East Cork Reclaim Childhood
</x-mail::message>
