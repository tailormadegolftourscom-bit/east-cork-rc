<x-mail::message>
# School Access Has Been Prepared

Hello,

A school user account has been prepared for **{{ $school->name }}**. You'll receive a separate email
with a secure link to set a password and access the school area.

<x-mail::panel>
**{{ $school->name }}**<br>
{{ ucfirst($school->school_type) }} school{{ $school->town ? ' · ' . $school->town : '' }}
</x-mail::panel>

Once logged in, you'll be able to:

- Update your school's contact details
- Add and edit class information and pupil totals
- Choose whether your school is listed as supporting the initiative

This is a parent-led initiative — schools are welcome to take part, but nothing here is required.

Thanks,<br>
East Cork Reclaim Childhood
</x-mail::message>
