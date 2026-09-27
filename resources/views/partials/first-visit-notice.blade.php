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
                        <h2 class="h3 fw-bold mb-0" id="ecrcNoticeTitle">Generation Alpha Rebels</h2>
                        <p class="h5 text-muted mb-0">East Cork</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <h3 class="h5">Our Kids Are Gen Alpha Rebels</h3>
                    <p>
                        The aim is simple: help children <strong>delay social media access</strong> for as long as possible into secondary school, knowing that other children in their class are doing the same.
                    </p>
                    <p>
                        <strong>Gen Alpha Rebels</strong> comes from <em><strong>The Amazing Generation</strong></em>, recommended reading for your child.
                    </p>

                    <p>Let&rsquo;s encourage them, back them and keep them motivated.</p>

                    <h3 class="h5">Replacing screens with activity — starting now</h3>
                    <p>
                        Reclaim Childhood is also about giving children more opportunities to meet, play and do
                        things together away from screens.
                    </p>

                    <p>
                        Starting with <strong>Monday badminton at Midleton Community Centre</strong> and a
                        <strong>Greenway Cycle on the Midleton&ndash;Youghal Greenway</strong> on Sunday 18th October at
                        1:00 p.m., to round off the CyberBreak weekend.
                    </p>

                    <p>
                        More activities will follow &mdash; and <strong>we want parents and children to send in their suggestions for activities and/or venues.</strong>
                    </p>

                    <p>
                        <strong>Register your child as a Rebel today &mdash; it takes two minutes &mdash; then tell
                        one other parent in their class.</strong> Not a parent here? Grandparents, family and
                        neighbours can <a href="{{ route('supporters.create') }}">join as supporters</a>.
                    </p>

                    @if ($tuesday || $thursday)
                        <p class="mb-0">
                            Want to talk it through first? We're holding informal meetings at {{ $notice['venue'] }}
                            on {{ collect([$tuesday['label'] ?? null, $thursday['label'] ?? null])->filter()->implode(' and ') }}.
                            <a href="{{ route('workshops.rsvp') }}">RSVP here</a>.
                        </p>
                    @endif

                    <div class="mt-4">
                        <div class="fw-semibold">Peter O'Sullivan</div>
                        <div>Interim Convenor</div>
                        <div class="text-muted small">{{ $notice['dated'] }}</div>
                    </div>
                </div>

                <div class="modal-footer flex-column align-items-stretch gap-2">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <a href="{{ route('register') }}" class="btn btn-success btn-lg w-100">
                                Register Your Rebel
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('activities') }}" class="btn btn-primary btn-lg w-100">
                                See What's On
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
            if (!el) return;

            var version = el.dataset.noticeVersion;
            var key = 'ecrc_notice_seen';

            // Storing the version rather than a boolean is what lets a later
            // notice reach people who dismissed the last one: bump the version
            // in config and every stored value stops matching.
            var seen = null;
            try { seen = window.localStorage.getItem(key); } catch (e) { /* private mode */ }

            if (seen === version) return;

            function remember() {
                try { window.localStorage.setItem(key, version); } catch (e) { /* ignore */ }
            }

            function open() {
                var modal = new window.bootstrap.Modal(el);
                modal.show();

                // Written on dismissal, not on show, so someone who closes the
                // tab mid-read is offered it again.
                el.addEventListener('hidden.bs.modal', remember);

                // Following a link is also a dismissal — otherwise the modal
                // reappears the moment they come back from the RSVP page.
                el.querySelectorAll('a[href]').forEach(function (link) {
                    link.addEventListener('click', remember);
                });
            }

            // Bootstrap arrives in a `type="module"` bundle, which is deferred
            // and therefore runs after this inline script. Waiting for it is
            // the whole reason this is not a plain call: checking for
            // `bootstrap` here would always find it missing.
            var attempts = 0;

            (function waitForBootstrap() {
                if (window.bootstrap && window.bootstrap.Modal) {
                    open();

                    return;
                }

                if (attempts++ > 100) return; // ~5s; the bundle is not coming

                window.setTimeout(waitForBootstrap, 50);
            })();
        })();
    </script>
@endif
