@extends('layouts.app')

@php($pageTitle = 'East Cork Reclaim Childhood')

@section('content')
    <section class="py-5 py-lg-6">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge text-bg-warning mb-3">Parent-led initiative</span>
                <h1 class="display-5 fw-bold mb-3">A parent-led movement to delay smartphones in East Cork</h1>
                <p class="lead text-muted mb-4">
                    East Cork Reclaim Childhood brings parents together to delay smartphones through primary school
                    and support a stronger transition into secondary school. Schools are welcome to support this,
                    but no school is under any obligation to take part.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('parents') }}" class="btn btn-primary btn-lg">For Parents</a>
                    <a href="{{ route('schools.index') }}" class="btn btn-outline-primary btn-lg">For Schools</a>
                </div>

                <div class="d-flex flex-wrap gap-3 small">
                    <a href="{{ route('school-registration.create') }}" class="text-decoration-none">Add My School</a>
                    <a href="{{ route('schools.index') }}" class="text-decoration-none">See Schools in East Cork</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Why this matters</h2>
                        <p class="text-muted mb-3">
                            Families often find it easier to delay smartphones when they know other parents are doing the same.
                        </p>
                        <ul class="mb-0 text-muted">
                            <li>Build support school by school</li>
                            <li>Start early from 4th class onward</li>
                            <li>Carry support into 1st year</li>
                            <li>Reduce pressure on individual families</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">For Parents</h2>
                        <p class="text-muted">
                            Join other parents at your child’s school. Add your child’s class details securely, see
                            where support is building, and help create a stronger local norm around delaying
                            smartphones through primary school.
                        </p>
                        <a href="{{ route('parents') }}" class="btn btn-primary">Parents: Get Started</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">For Schools</h2>
                        <p class="text-muted">
                            Schools are not being asked to endorse a campaign or take on extra obligations. Where a
                            school chooses to support the initiative, it can nominate a school user to update class
                            sizes and school contact details.
                        </p>
                        <a href="{{ route('schools.index') }}" class="btn btn-outline-primary">Schools: Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="card border-primary shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h2 class="h3 mb-2">Is your school missing? Add it here.</h2>
                        <p class="text-muted mb-0">
                            Parents can request that a school be added even if the school itself has not yet engaged.
                            If a school later chooses to support the initiative, it can be given secure access to
                            update its own class information and contact details.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('school-registration.create') }}" class="btn btn-primary btn-lg">Add My School</a>
                    </div>
                </div>
                <div class="small mt-3">Parent-led first. School support welcome, but not required.</div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <h2 class="h2">How it works</h2>
            <p class="text-muted mb-0">A simple local process, built around schools, classes and parent support.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary mb-3">1</div>
                        <h3 class="h5">Parents join at school level</h3>
                        <p class="text-muted mb-0">
                            Parents register under their child’s school and class so support can be built in a practical,
                            local way.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary mb-3">2</div>
                        <h3 class="h5">Support builds from 4th class onward</h3>
                        <p class="text-muted mb-0">
                            The aim is to help parents start early, strengthen support through 4th, 5th and 6th class,
                            and carry that support into 1st year at secondary school.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary mb-3">3</div>
                        <h3 class="h5">Schools may support, but parents lead</h3>
                        <p class="text-muted mb-0">
                            Some schools may help by confirming class sizes or contact information. Parent groups can
                            still move forward where a school does not participate formally.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h3 mb-3">This is a parent-led initiative</h2>
                        <p class="text-muted mb-0">
                            East Cork Reclaim Childhood is built by parents, for parents. It is designed to make it
                            easier for families to act together rather than alone. No school is under any obligation
                            to participate. No child or family is judged. The aim is simply to help parents connect,
                            reduce pressure, and support a healthier childhood.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h3 mb-3">Schools can support at their own pace</h2>
                        <p class="text-muted mb-0">
                            If a school chooses to support the initiative, it can nominate a secure school user who can
                            update class sizes and school contact details. This helps keep school information accurate
                            and reduces admin duplication. If a school prefers not to participate directly, the school
                            can still appear on the site and be managed by admin so parents can organise at local level.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <h2 class="h2">Building support across East Cork</h2>
            <p class="text-muted mb-0">
                Some schools will begin with parent-led support only. Others may later choose to support the
                initiative directly. Real numbers, updated as they grow.
            </p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary">{{ $stats['schools_listed'] }}</div>
                        <div class="text-muted">Schools listed</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary">{{ $stats['schools_supporting'] }}</div>
                        <div class="text-muted">Schools Supporting</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary">{{ $stats['parents_registered'] }}</div>
                        <div class="text-muted">Parents registered</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-6 fw-bold text-primary">{{ $stats['children_registered'] }}</div>
                        <div class="text-muted">Children registered</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="text-center mb-5">
            <h2 class="h2">Common questions</h2>
            <p class="text-muted mb-0">Clear answers to the main issues parents and schools raise.</p>
        </div>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Is this anti-technology?
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No. It is about delaying smartphones, not rejecting technology altogether.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        What if my child already has a smartphone?
                    </button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Families are at different stages. Parents can still join and take part.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        What is a balance phone?
                    </button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        A balance phone is a simpler alternative for contact and safety where a full smartphone feels too much, too soon.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        What if my school does not want to take part?
                    </button>
                </h3>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Parents can still organise at school level. School support is welcome, but not required.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                        Can a school update its own details?
                    </button>
                </h3>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes. If a school chooses to support the initiative, it can be given secure access to manage class sizes and contact details.
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('faqs') }}" class="btn btn-outline-primary">Read All FAQs</a>
        </div>
    </section>

    <section class="py-5">
        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body p-4 p-lg-5 text-center">
                <h2 class="h2 mb-3">Stay informed as the network grows</h2>
                <p class="mb-4 text-white-50">
                    Get occasional updates as more schools, classes and local parent groups are added across East Cork.
                </p>
                <a href="{{ route('updates.create') }}" class="btn btn-warning btn-lg">Get Updates</a>
            </div>
        </div>
    </section>
@endsection
