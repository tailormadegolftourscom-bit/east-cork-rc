@extends('layouts.app')

@php($pageTitle = 'FAQs — East Cork Reclaim Childhood')

@section('content')
    <section class="py-5">
        <h1 class="display-6 fw-bold mb-3">Frequently Asked Questions</h1>
        <p class="lead text-muted mb-5" style="max-width: 46rem;">
            Clear answers to the questions parents and schools raise most often.
        </p>

        <div class="accordion mb-5" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Is this anti-technology?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No. A phone for calls and texts is fine by us at any age. It's social media specifically —
                        the apps built to be addictive — that we're holding off on, and giving kids more of a real
                        childhood in the meantime.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1a">
                        Why delay social media at all?
                    </button>
                </h2>
                <div id="faq1a" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The background is set out on <a href="{{ route('the-issue') }}">The Issue</a>, and
                        <a href="https://smartphonefree.ie/" target="_blank" rel="noopener">Smartphone Free Childhood
                        Ireland</a> covers the evidence in depth. This site concentrates on the practical side: parents
                        acting together locally, and giving children better things to do.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqRebel">
                        What is a Gen Alpha Rebel?
                    </button>
                </h2>
                <div id="faqRebel" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Any child whose family has joined East Cork Reclaim Childhood. The name comes from
                        <em>The Amazing Generation</em> by Jonathan Haidt and Catherine Price, where the Rebels take on
                        the &ldquo;Greedy Wizards&rdquo; who build apps to keep children hooked. Our quarrel is with
                        the Wizards, never with other children. We treat Rebels as heroes and want to encourage them in
                        any way we can &mdash; see <a href="{{ route('for-kids') }}">For Kids</a>.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1b">
                        Why 16? That sounds like a long time.
                    </button>
                </h2>
                <div id="faq1b" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        16 is a stretch goal, not a rule. The honest aim is as late as reasonably possible, and every
                        year gained matters — some families will land earlier than that, and that's still a win.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        What if my child already has a smartphone?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Families are all at different stages, and there's no judgment here. You can still join, still
                        hold off on social media specifically, and still help build momentum for younger classes and
                        other families.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSupporter">
                        I'm not a parent here. Can I help?
                    </button>
                </h2>
                <div id="faqSupporter" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes. Grandparents, aunts and uncles, neighbours, teachers and coaches are all welcome.
                        <a href="{{ route('supporters.create') }}">Join as a supporter</a> &mdash; no account or
                        password needed &mdash; to be counted, share ideas, or offer to help. You can also volunteer for
                        a particular activity, such as marshalling on the Greenway cycle, straight from its page on
                        <a href="{{ route('activities') }}">What's On</a>.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        What is a "balance" phone?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        A simpler phone for calls, texts and maybe maps, with no app store and no social media. For a
                        lot of families this isn't a stopgap — it's the actual plan right up to 16. See
                        <a href="{{ route('resources') }}">Resources</a> for more.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        What if my school doesn't want to take part?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Parents can still organise at school and class level without the school's involvement. School
                        support is welcome, genuinely useful, and never required.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                        Can a school update its own details?
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes. A school that chooses to support the initiative can be given secure access to manage its
                        own class list and pupil totals, and its contact details.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqGroups">
                        What are Action Groups?
                    </button>
                </h2>
                <div id="faqGroups" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>
                            Action Groups are how things get organised. There's an East Cork Action Group for the whole
                            area, a group for each school, and under each school a group for each year &mdash; for
                            example, all of 6th Class &mdash; with individual class groups below that where a class
                            wants its own. There are also groups for particular activities and events, like the Midleton
                            Badminton Group. Each group has a convenor: the person others can contact, who sets out what
                            the group is for. Any registered parent can join a group or step forward as convenor &mdash;
                            see <a href="{{ route('committees.index') }}">Action Groups</a>.
                        </p>
                        <p class="mb-0">
                            <strong>The <a href="{{ route('committees.show', 'east-cork-action-group') }}">East Cork
                            Action Group</a> especially needs more members.</strong> If you could help get things going
                            across the area, please join it.
                        </p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqActivities">
                        How do activities work?
                    </button>
                </h2>
                <div id="faqActivities" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Everything that's on is listed on <a href="{{ route('activities') }}">What's On</a>. For each
                        activity you can sign your registered Rebels up, so the convenor knows who to expect &mdash; for
                        weekly sessions like badminton, one sign-up covers the regular sessions. Once you've signed up,
                        you'll see the activity's WhatsApp group link, if it has one, for updates on the day. Some
                        activities, like the Greenway cycle, also need volunteers, and anyone can offer to help on the
                        activity's page.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                        Does my real name appear on the website?
                    </button>
                </h2>
                <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Only if you choose to show it. When you register, you can pick an anonymous supporter code
                        instead (for example, "AD00001") that's shown publicly rather than your real name. Your real
                        details are never shown to anyone except site administrators, and are never shared publicly.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                        Does my child's name appear anywhere public?
                    </button>
                </h2>
                <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Not to the public. Anyone who isn't logged in sees only numbers for each school and class.
                        Other registered parents, once logged in, can see the children in each class by their
                        <strong>Rebel code name</strong> &mdash; and by first name as well, but only if you choose that
                        when registering your child. Surnames are never shown. You can change these settings at any
                        time by editing your child's details.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                        Is this only for East Cork?
                    </button>
                </h2>
                <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        It's organised around East Cork schools, but parents from anywhere are welcome to register
                        or <a href="{{ route('supporters.create') }}">join as a supporter</a>, and to borrow the idea
                        for their own area.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                        How does this fit with Smartphone Free Childhood Ireland?
                    </button>
                </h2>
                <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        East Cork Reclaim Childhood is an extension of the work of
                        <a href="https://smartphonefree.ie/" target="_blank" rel="noopener">Smartphone Free Childhood
                        Ireland</a> (SFCI), bringing it to a local level. SFCI leads the national movement, with its
                        pledge and WhatsApp groups right across the country. ECRC concentrates on the local, practical
                        side: parents in the same East Cork schools and classes acting together, and activities that
                        get children out and together. Families are welcome to take part in both.
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-muted mb-2">Still have a question?</p>
            <a href="mailto:info@eastcorkreclaimchildhood.ie" class="btn btn-outline-primary">Contact Us</a>
        </div>
    </section>
@endsection
