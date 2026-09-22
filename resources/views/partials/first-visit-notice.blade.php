@php($notice = config('notice'))

@if (($notice['enabled'] ?? false) && ! request()->is('workshops/*'))
    @php($tuesday = $notice['workshops']['tuesday'] ?? null)
    @php($thursday = $notice['workshops']['thursday'] ?? null)

    <div class="modal fade" id="ecrcNotice" tabindex="-1" aria-labelledby="ecrcNoticeTitle" aria-hidden="true"
         data-notice-version="{{ $notice['version'] }}">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h2 class="h4 mb-1" id="ecrcNoticeTitle">
                            East Cork Reclaim Childhood &mdash; An Invitation to Get Involved
                        </h2>
                        <p class="text-muted small mb-0">{{ $notice['dated'] }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        East Cork Reclaim Childhood (ECRC) is a new, parent-led extension of the work of
                        <strong>Smartphone Free Childhood Ireland (SFCI)</strong>, focused on practical support for
                        families here in East Cork.
                    </p>

                    <p>
                        At its heart is a simple idea: <strong>parents working together to delay their children's
                        access to social media until well into secondary school</strong>.
                    </p>

                    <p>
                        ECRC is still very much in its infancy. Nothing is set in stone. The proposals, objectives
                        and wording on this website are intended as a starting point for discussion with parents,
                        schools and the wider community.
                    </p>

                    <h3 class="h6 mt-4">What could Reclaim Childhood look like?</h3>
                    <p class="mb-2">As I see it, there are two important parts:</p>

                    <p class="mb-2">
                        <strong>1. Help children stay engaged with the agreement to delay social media.</strong><br>
                        That could mean events and shared experiences that make being part of the group something
                        positive &mdash; for example, a <strong>monster family BBQ in June</strong> or other events
                        suggested by parents and children.
                    </p>

                    <p>
                        <strong>2. Create more opportunities to replace screen time with real-world activity.</strong><br>
                        Ideas might include <strong>badminton at Midleton Community Centre</strong>,
                        <strong>family cycles on the Midleton&ndash;Youghal Greenway</strong>, walks, kickarounds,
                        games and other simple activities that bring children together.
                    </p>

                    <p>
                        These are only examples.
                        <strong>We would particularly like parents to share their own ideas.</strong>
                    </p>

                    <h3 class="h6 mt-4">Help shape what happens next</h3>
                    <p>
                        We propose to hold two informal workshops at <strong>{{ $notice['venue'] }}</strong> to
                        discuss the aims, structure and direction of ECRC:
                    </p>

                    <ul>
                        @if ($tuesday)<li><strong>{{ $tuesday['label'] }}</strong></li>@endif
                        @if ($thursday)<li><strong>{{ $thursday['label'] }}</strong></li>@endif
                    </ul>

                    <p>
                        Parents are welcome to attend either meeting. Please RSVP so that we have an idea of numbers.
                    </p>

                    <h3 class="h6 mt-4">You can also begin by registering your child or children</h3>
                    <p>
                        Parents who are already comfortable with the central aim can register their child or
                        children on the site.
                    </p>

                    <p>
                        Doing so provides a clear indication that your family intends to <strong>delay social media
                        access for as long as possible into secondary school</strong>, while also helping other
                        parents see that they are not making that decision alone.
                    </p>

                    <p class="mb-0">
                        <strong>This works best when parents know that other families are making the same choice.</strong>
                    </p>
                </div>

                <div class="modal-footer flex-column align-items-stretch gap-2">
                    <div class="row g-2">
                        @if ($tuesday)
                            <div class="col-sm-6">
                                <a href="{{ route('workshops.rsvp', ['workshop' => 'tuesday']) }}"
                                   class="btn btn-primary w-100">RSVP &ndash; {{ $tuesday['short'] }}</a>
                            </div>
                        @endif
                        @if ($thursday)
                            <div class="col-sm-6">
                                <a href="{{ route('workshops.rsvp', ['workshop' => 'thursday']) }}"
                                   class="btn btn-primary w-100">RSVP &ndash; {{ $thursday['short'] }}</a>
                            </div>
                        @endif
                        <div class="col-sm-6">
                            <a href="{{ route('register') }}" class="btn btn-success w-100">
                                Register My Child/Children
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('supporters.create') }}" class="btn btn-outline-primary w-100">
                                Share an Idea
                            </a>
                        </div>
                    </div>

                    <button type="button" class="btn btn-link btn-sm text-muted" data-bs-dismiss="modal">
                        Continue to website
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var el = document.getElementById('ecrcNotice');
            if (!el || typeof bootstrap === 'undefined') return;

            var version = el.dataset.noticeVersion;
            var key = 'ecrc_notice_seen';

            // Storing the version rather than a boolean is what lets a later
            // notice reach people who dismissed the last one: bump the version
            // in config and every stored value stops matching.
            var seen = null;
            try { seen = window.localStorage.getItem(key); } catch (e) { /* private mode */ }

            if (seen === version) return;

            var modal = new bootstrap.Modal(el);
            modal.show();

            // Written on dismissal, not on show, so someone who closes the tab
            // mid-read is offered it again.
            el.addEventListener('hidden.bs.modal', function () {
                try { window.localStorage.setItem(key, version); } catch (e) { /* ignore */ }
            });

            // Following a link is also a dismissal — otherwise the modal
            // reappears the moment they come back from the RSVP page.
            el.querySelectorAll('a[href]').forEach(function (link) {
                link.addEventListener('click', function () {
                    try { window.localStorage.setItem(key, version); } catch (e) { /* ignore */ }
                });
            });
        })();
    </script>
@endif
