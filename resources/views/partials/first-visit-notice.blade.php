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
                            Parents of East Cork: your child's class needs you now
                        </h2>
                        <p class="text-muted small mb-0">{{ $notice['dated'] }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Social media arrives in a class one family at a time, each giving in because they think
                        everyone else already has. <strong>The only thing that stops it is parents deciding
                        together, early</strong> &mdash; and that means now, not next year.
                    </p>

                    <h3 class="h6 mt-4">Our kids are Gen Alpha Rebels</h3>
                    <p>
                        Borrowing from Jonathan Haidt and Catherine Price's <em>The Amazing Generation</em>, kids who
                        join are <strong>Gen Alpha Rebels</strong>. Rebels take on the <strong>Greedy
                        Wizards</strong> &mdash; the companies that build apps to keep them hooked &mdash; never their
                        fellow students. It starts in 4th class, carries through to an end-of-year party for 4th,
                        5th and 6th, and on into secondary school with the Rebels going to the same school.
                    </p>

                    <h3 class="h6 mt-4">Replacing screens with activity &mdash; starting now</h3>
                    @php($planned = \App\Models\Activity::with('venue')->listed()->where('status', 'planned')->take(4)->get())
                    @if ($planned->isNotEmpty())
                        <ul>
                            @foreach ($planned as $activity)
                                <li>
                                    <strong>{{ $activity->title }}</strong>
                                    @if ($activity->venue) at {{ $activity->venue->label }} @endif
                                    @if ($activity->whenLabel()) &mdash; {{ $activity->whenLabel() }} @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Activities are being arranged now. <a href="{{ route('activities') }}">See what's planned</a>.</p>
                    @endif

                    <p class="mb-0">
                        <strong>Register your child as a Rebel today &mdash; it takes two minutes &mdash; then tell
                        one other parent in their class.</strong> Not a parent here? Grandparents, family and
                        neighbours can join as supporters.
                    </p>

                    @if ($tuesday || $thursday)
                        <p class="small text-muted mt-4 mb-0">
                            Want to talk it through first? We're holding informal meetings at {{ $notice['venue'] }}
                            on {{ collect([$tuesday['label'] ?? null, $thursday['label'] ?? null])->filter()->implode(' and ') }}.
                            <a href="{{ route('workshops.rsvp') }}">RSVP here</a>.
                        </p>
                    @endif
                </div>

                <div class="modal-footer flex-column align-items-stretch gap-2">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <a href="{{ route('register') }}" class="btn btn-success btn-lg w-100">
                                Register Your Rebel Now
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('activities') }}" class="btn btn-primary btn-lg w-100">
                                See What's On
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('supporters.create') }}" class="btn btn-outline-primary w-100">
                                Join as a Supporter
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
