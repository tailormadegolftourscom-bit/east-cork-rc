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
                        No. This is about delaying smartphones and social media specifically, and giving kids more of a
                        real childhood in the meantime — not rejecting technology altogether.
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
                        support the initiative, and still help build momentum for younger classes and other families.
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
                        A simpler device for calls and texts, with no app store and no social media — a practical
                        middle step for families who want their child contactable without handing over a full
                        smartphone. See <a href="{{ route('resources') }}">Resources</a> for more.
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
                        No. Children's real names are never shown publicly. We ask for a real first name at
                        registration so the information behind the scenes is genuine, but public participation figures
                        are always shown as counts, not names.
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
                        East Cork is our pilot area. If it works well here, we'd like to see the same approach used
                        in other areas across Ireland — see <a href="{{ route('about') }}">About</a>.
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
