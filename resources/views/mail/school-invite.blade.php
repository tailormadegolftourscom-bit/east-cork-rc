<x-mail::message>
Hello,

I am writing from East Cork Reclaim Childhood (ECRC), a parent-led initiative building on the work of Smartphone
Free Childhood Ireland and bringing that work to a more local level in East Cork.

Our aim is to support families who want to delay children's access to smartphones and, in particular, social
media for as long as possible, while also putting more emphasis on what replaces screen time: real-world
friendships, outdoor play, sport, hobbies and local activities for children and families.

This email is primarily for information and there is no action required from the school. ECRC is run by parents, although we would of course greatly appreciate the school's support where it is comfortable offering it. The one practical area where the school could be particularly helpful is by confirming the names of the individual 4th, 5th and 6th Classes, together with the number of pupils in each, as this information is important to how the parent network is organised.

You can read more about the initiative at:

[eastcorkreclaimchildhood.ie]({{ url('/') }})

A school account has now been prepared for **{{ $school->name }}**. The button below will let you choose a
password and open the school area.

@isset($setPasswordUrl)
<x-mail::button :url="$setPasswordUrl">
Set My Password
</x-mail::button>

The link lasts 24 hours. If it has expired by the time you get to it, there is a button on that page to send
a fresh one, so nothing is lost either way.
@endisset

The school account gives you the option to review and update information about the school, including contact
details and class information.

Class names might look like:

- 6th Class A / 6th Class B
- 6th Class – Peter
- 6th Class – Cathy
- Rang 6A / Rang 6B

This will allow parents to see support at the level of individual classes, rather than only at whole-school
level, and should make it easier for parents of children in the same class to connect with and support one
another.

If preferred, this class information can also be added or updated by one of our administrators, so the school
does not need to manage it directly.

East Cork Reclaim Childhood is still at an early stage. I am happy to meet with parents, schools and other
interested people to help shape the objectives, practical arrangements and longer-term direction of the
initiative.

I hope the project will become a useful local support for families and schools across East Cork, and I would
be very happy to hear any comments, concerns or suggestions you may have.

Kind regards,

Peter O'Sullivan<br>
East Cork Reclaim Childhood<br>
Interim Convenor
</x-mail::message>
