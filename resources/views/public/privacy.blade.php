@extends('layouts.app')

@php($pageTitle = 'Privacy & Data Protection — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <div style="max-width: 50rem;">
            <h1 class="display-6 fw-bold mb-2">Privacy &amp; Data Protection Notice</h1>
            <p class="text-muted mb-4">Last updated 28 September 2026</p>

            <div class="card border-0 bg-light mb-5">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">In short</h2>
                    <ul class="mb-0">
                        <li>Your child's name is <strong>never shown publicly</strong>. Other registered parents see a Rebel code name &mdash; plus first name only if you choose.</li>
                        <li>Your email address and phone number are <strong>never shown</strong> to other parents or the public.</li>
                        <li>Your details are <strong>never shared</strong> with schools, Smartphone Free Childhood Ireland or any other organisation.</li>
                        <li>You can see, change or delete your information at any time &mdash; email
                            <a href="mailto:info@eastcorkreclaimchildhood.ie">info@eastcorkreclaimchildhood.ie</a>.</li>
                    </ul>
                </div>
            </div>

            <h2 class="h4 mt-5 mb-3">Who is responsible for your information</h2>
            <p>
                East Cork Reclaim Childhood (ECRC) is a voluntary, parent-led initiative. The person responsible for
                your information (the &ldquo;data controller&rdquo;) is <strong>Peter O'Sullivan, Interim Convenor</strong>.
                For anything about your information, email
                <a href="mailto:info@eastcorkreclaimchildhood.ie">info@eastcorkreclaimchildhood.ie</a>.
            </p>

            <h2 class="h4 mt-5 mb-3">What we collect, and why</h2>

            <h3 class="h6 mt-4">When you register as a parent</h3>
            <p>
                Your name, email address and password, and &mdash; if you give them &mdash; your phone number and how
                you'd like to be contacted. We use these to run your account and contact you about ECRC. Your password
                is stored in a form nobody can read, including us.
            </p>

            <h3 class="h6 mt-4">When you register a child</h3>
            <p>
                Your child's first name, and last name if you give it; a Rebel code name; their school and class; for
                6th class, the secondary school they intend to go to; and who their parents are. We use these to record
                participation, show support within schools and classes, help parents form local groups, and bring
                together Rebels going to the same secondary school.
            </p>

            <h3 class="h6 mt-4">Activities, groups and events</h3>
            <p>
                If you sign your child up for an activity, we record which child is coming so the convenor knows who to
                expect. If you volunteer, we record your name, email, phone number and any note, so the convenor can
                organise helpers. If you join an Action Group, we record your membership. If you RSVP to a meeting or
                send in a suggestion, we record what you send.
            </p>

            <h3 class="h6 mt-4">Supporters</h3>
            <p>
                If you join as a supporter, we record your name, email, any phone number and message, how you'd like
                to help, and how you'd like to be contacted.
            </p>

            <h3 class="h6 mt-4">Schools</h3>
            <p>
                The school contact details used to invite schools to take part (names and work email addresses of
                principals and secretaries), and the details of anyone who asks for a school to be added.
            </p>

            <h2 class="h4 mt-5 mb-3">Who can see what</h2>
            <ul>
                <li><strong>The public</strong> sees only numbers &mdash; how many children are registered at each school and class &mdash; plus activity headcounts and approved suggestions, never with names.</li>
                <li><strong>Other registered parents</strong>, when logged in, see children in each class by Rebel code name, and by first name only where the parent chose that. Surnames are never shown.</li>
                <li><strong>Action Group pages</strong> show members by name only if they choose &ldquo;show my name&rdquo;; otherwise by an anonymous code.</li>
                <li><strong>Activity WhatsApp group links</strong> are shown only to parents who have signed up for, or volunteered for, that activity. If you join a WhatsApp group, other members can see your phone number &mdash; that is part of how WhatsApp works.</li>
                <li><strong>ECRC's administrator</strong> can see all the details above, to run the site and the activities.</li>
            </ul>

            <h2 class="h4 mt-5 mb-3">Our legal basis</h2>
            <p>
                For everything you sign up for &mdash; your account, registering your child, what other parents see,
                activities, volunteering, groups, supporting, updates and RSVPs &mdash; we rely on your
                <strong>consent</strong>. You can withdraw it at any time; we will then delete the information
                concerned.
            </p>
            <p>
                For keeping the site secure (a short-lived record of each visit, including its IP address and browser type, and a record of administrative actions) and for school
                contact details used to invite schools, we rely on our <strong>legitimate interests</strong> in running
                a safe site and reaching schools. You can object to this at any time.
            </p>

            <h2 class="h4 mt-5 mb-3">Who we share it with</h2>
            <p>
                <strong>Nobody.</strong> We never share, sell or pass on your information to schools, Smartphone Free
                Childhood Ireland, or any other organisation. The only outside services involved are the ones that run
                the site for us:
            </p>
            <ul>
                <li><strong>Vultr</strong> &mdash; hosts the website and its database.</li>
                <li><strong>RunCloud</strong> &mdash; the panel used to manage the server.</li>
                <li><strong>Resend</strong> &mdash; sends the site's emails.</li>
                <li><strong>Zoho</strong> &mdash; hosts the info@ mailbox, which receives copies of some site emails.</li>
            </ul>

            <h2 class="h4 mt-5 mb-3">Where it is stored</h2>
            <p>
                The website and its database are on a server in the United States, and Resend stores sent emails in
                the United States. These transfers are covered by each provider's GDPR data processing agreement.
                Resend's includes the European Commission's Standard Contractual Clauses, and Resend is also
                certified under the EU&ndash;US Data Privacy Framework. If ECRC becomes established as a registered
                not-for-profit organisation, the site will move to a server in the European Union.
            </p>

            <h2 class="h4 mt-5 mb-3">How long we keep it</h2>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr><th scope="row">Your child's registration</th><td>Until you delete it, or the end of your child's first year in secondary school</td></tr>
                    <tr><th scope="row">Your parent account</th><td>While you have a registered child, or until you close it; deleted 12 months after your last child's registration ends</td></tr>
                    <tr><th scope="row">Activity sign-ups and volunteers</th><td>Deleted 3 months after the activity; for weekly activities, when you withdraw</td></tr>
                    <tr><th scope="row">Meeting RSVPs</th><td>Deleted 3 months after the meetings</td></tr>
                    <tr><th scope="row">Supporters</th><td>Until you ask to be removed, or after 2 years with no contact</td></tr>
                    <tr><th scope="row">Registrations never completed</th><td>Deleted after 9 days</td></tr>
                    <tr><th scope="row">Record of administrative actions</th><td>2 years</td></tr>
                    </tbody>
                </table>
            </div>

            <h2 class="h4 mt-5 mb-3">Your rights</h2>
            <p>You can ask us, at any time and free of charge, to:</p>
            <ul>
                <li>see the information we hold about you or your child;</li>
                <li>correct anything that's wrong;</li>
                <li>delete it;</li>
                <li>withdraw your consent, or object to our use of it;</li>
                <li>give you a copy to take elsewhere.</li>
            </ul>
            <p>
                You can edit or delete your child's details yourself from your account. For anything else, email
                <a href="mailto:info@eastcorkreclaimchildhood.ie">info@eastcorkreclaimchildhood.ie</a>. We will reply
                within one month.
            </p>
            <p>
                If you're unhappy with how we've handled your information, you can complain to the
                <a href="https://www.dataprotection.ie/" target="_blank" rel="noopener">Data Protection Commission</a>,
                Ireland's data protection regulator.
            </p>

            <h2 class="h4 mt-5 mb-3">Children</h2>
            <p>
                Children are registered by a parent or guardian, never by themselves. Children have the same rights
                over their information as adults, and a child can ask &mdash; directly or through a parent &mdash; for
                their information to be changed or deleted. There is a short explanation written for children on the
                <a href="{{ route('for-kids') }}#your-information">For Kids</a> page.
            </p>

            <h2 class="h4 mt-5 mb-3">Cookies and analytics</h2>
            <p>
                The site uses only the cookies it needs to work: one that runs your visit and keeps you logged in, and
                one that protects its forms against misuse. It also remembers in your browser whether you've already seen the welcome
                messages, so they don't keep reappearing; that stays on your device.
                We count visits with <strong>Plausible</strong>, which uses no cookies and collects no personal
                information, so no cookie banner is needed.
            </p>

            <h2 class="h4 mt-5 mb-3">Changes to this notice</h2>
            <p class="mb-0">
                If we change how we use your information, we'll update this page and the date at the top.
            </p>
        </div>
    </section>
@endsection
