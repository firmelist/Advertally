{{-- Technology & Talent enquiry — a separate buyer journey from the growth strategy request. --}}
<section id="enquire" class="section scroll-mt-20 bg-canvas" aria-labelledby="enquire-title">
    <div class="container-x grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
        <div>
            <p class="eyebrow">Technology &amp; Talent</p>
            <h2 id="enquire-title" class="h-section mt-3">Tell us who you need.</h2>
            <p class="mt-5 text-lg leading-relaxed text-muted">Share the role, scope and timeline. We will come back within one business day with matched profiles or a team proposal — and an honest view if a different model would serve you better.</p>
            <ul class="mt-8 space-y-3 text-[15px] text-ink">
                @foreach (['Specialists vetted for skills and communication', 'Work inside your tools, rituals and time zone', 'Scale up or down with clear notice periods', 'Optional Advertally growth oversight'] as $point)
                    <li class="flex gap-3"><x-glyph name="check-circle" class="mt-0.5 size-5 text-brand-600" /> {{ $point }}</li>
                @endforeach
            </ul>
        </div>
        <form method="POST" action="{{ route('contact.store') }}" class="card relative space-y-5 p-6 sm:p-8" novalidate>
            @csrf
            <input type="hidden" name="form" value="talent">
            <x-form.guard />
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field name="name" label="Your name" required autocomplete="name" />
                <x-form.field name="company" label="Company" required autocomplete="organization" />
                <x-form.field name="email" type="email" label="Business email" required autocomplete="email" />
                <x-form.field name="phone" type="tel" label="Phone" autocomplete="tel" />
            </div>
            <x-form.field name="talent_role" label="Who do you need?" :options="config('advertally.talent_roles')" required />
            <x-form.field name="message" type="textarea" label="Scope, skills and timeline" rows="4" placeholder="e.g. Two Laravel developers for 6 months, starting next month, working with our product team." />
            <x-form.consent />
            <button class="btn-primary btn-lg w-full" data-track="talent_enquiry">Discuss my requirement <x-glyph name="arrow-right" class="size-4" /></button>
        </form>
    </div>
</section>
