@props([
    'formType' => 'contact',
    'formId' => 'contact',
    'variant' => 'full',          // compact | full | hire
    'button' => 'Get My Free Proposal',
    'showWebsite' => false,
    'services' => [],             // pre-selected service keys
    'title' => null,
    'subtitle' => null,
    'dark' => false,
])
@php
    use App\Models\Lead;
    $mine = old('form_id') === $formId;          // only show server-side errors on the form that was submitted
    $err = fn ($f) => $mine ? $errors->first($f) : null;
    $val = fn ($f, $d = null) => $mine ? old($f, $d) : $d;
    $selected = $mine ? (array) old('services', []) : $services;
    $turnstileKey = config('advertally.turnstile.site_key');
    $hireRoles = ['Laravel / PHP Developer', 'React / Node Developer', 'Flutter / Mobile Developer', 'UI/UX Designer', 'SEO Executive', 'Performance Marketer', 'Social Media Manager', 'Full-stack Team', 'Other'];
    $uid = 'f-'.$formId;
@endphp

<form method="POST" action="{{ route('leads.store') }}" novalidate
      x-data="{
          sending: false,
          errors: @js($mine ? $errors->getMessages() : (object) []),
          async submit(e) {
              this.sending = true; this.errors = {};
              try {
                  const res = await fetch(e.target.action, {
                      method: 'POST',
                      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                      body: new FormData(e.target),
                  });
                  if (res.status === 422) { this.errors = (await res.json()).errors || {}; this.sending = false; return; }
                  if (res.status === 429) { this.errors = { name: ['Too many attempts. Please wait a minute or WhatsApp us.'] }; this.sending = false; return; }
                  const data = await res.json();
                  window.location = data.redirect;
              } catch (err) { e.target.submit(); }
          },
          e(f) { return this.errors[f] ? this.errors[f][0] : null; },
      }"
      @submit.prevent="submit($event)"
      {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @csrf
    <input type="hidden" name="form_type" value="{{ $formType }}">
    <input type="hidden" name="form_id" value="{{ $formId }}">
    <input type="hidden" name="source_page" value="{{ request()->getRequestUri() }}">
    <input type="hidden" name="_ts" value="{{ time() }}">
    {{-- Honeypot --}}
    <div class="hidden" aria-hidden="true"><label>Leave empty <input type="text" name="company_website" tabindex="-1" autocomplete="off"></label></div>

    @if ($title)
        <div>
            <p class="font-display text-xl font-bold {{ $dark ? 'text-white' : 'text-ink' }}">{{ $title }}</p>
            @if ($subtitle)<p class="mt-1 text-sm {{ $dark ? 'text-white/70' : 'text-muted' }}">{{ $subtitle }}</p>@endif
        </div>
    @endif

    <div @class(['grid gap-4', 'sm:grid-cols-2' => $variant !== 'compact'])>
        <div>
            <label for="{{ $uid }}-name" class="field-label">Your name <span class="text-red-600">*</span></label>
            <input id="{{ $uid }}-name" name="name" type="text" required autocomplete="name" value="{{ $val('name') }}" class="field" placeholder="Rahul Mehta">
            <p class="field-error" x-show="e('name')" x-text="e('name')">{{ $err('name') }}</p>
        </div>
        <div>
            <label for="{{ $uid }}-phone" class="field-label">Mobile / WhatsApp <span class="text-red-600">*</span></label>
            <div class="flex">
                <span class="inline-flex items-center rounded-l-xl border border-r-0 border-line bg-canvas px-3 text-sm text-muted">+91</span>
                <input id="{{ $uid }}-phone" name="phone" type="tel" inputmode="numeric" required autocomplete="tel-national" maxlength="14" value="{{ $val('phone') }}" class="field rounded-l-none" placeholder="98XXXXXXXX">
            </div>
            <p class="field-error" x-show="e('phone')" x-text="e('phone')">{{ $err('phone') }}</p>
        </div>
        <div>
            <label for="{{ $uid }}-email" class="field-label">Work email</label>
            <input id="{{ $uid }}-email" name="email" type="email" autocomplete="email" value="{{ $val('email') }}" class="field" placeholder="you@company.com">
            <p class="field-error" x-show="e('email')" x-text="e('email')">{{ $err('email') }}</p>
        </div>

        @if ($showWebsite)
            <div>
                <label for="{{ $uid }}-website" class="field-label">Website</label>
                <input id="{{ $uid }}-website" name="website" type="text" inputmode="url" value="{{ $val('website') }}" class="field" placeholder="yourbusiness.com">
            </div>
        @endif

        @if ($variant !== 'compact')
            <div>
                <label for="{{ $uid }}-company" class="field-label">Company name</label>
                <input id="{{ $uid }}-company" name="company" type="text" autocomplete="organization" value="{{ $val('company') }}" class="field" placeholder="Mehta Industries">
            </div>
        @endif
    </div>

    @if ($variant === 'full')
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="{{ $uid }}-size" class="field-label">Business size (annual turnover)</label>
                <select id="{{ $uid }}-size" name="business_size" class="field">
                    <option value="">Select…</option>
                    @foreach (config('advertally.business_sizes') as $k => $label)
                        <option value="{{ $k }}" @selected($val('business_size') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="{{ $uid }}-budget" class="field-label">Monthly budget</label>
                <select id="{{ $uid }}-budget" name="budget" class="field">
                    <option value="">Select…</option>
                    @foreach (config('advertally.budgets') as $k => $label)
                        <option value="{{ $k }}" @selected($val('budget') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <fieldset>
            <legend class="field-label">What do you need help with?</legend>
            <div class="flex flex-wrap gap-2">
                @foreach (Lead::SERVICE_OPTIONS as $k => $label)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="services[]" value="{{ $k }}" class="peer sr-only" @checked(in_array($k, $selected, true))>
                        <span class="inline-flex items-center rounded-full border border-line bg-white px-3 py-1.5 text-xs font-medium text-muted transition peer-checked:border-brand-600 peer-checked:bg-brand-600 peer-checked:text-white peer-focus-visible:outline-2 peer-focus-visible:outline-brand-600 hover:border-brand-300">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>
    @endif

    @if ($variant === 'hire')
        <input type="hidden" name="services[]" value="hire">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="{{ $uid }}-role" class="field-label">Role you want to hire</label>
                <select id="{{ $uid }}-role" name="role" class="field">
                    @foreach ($hireRoles as $role)<option @selected($val('role') === $role)>{{ $role }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="{{ $uid }}-exp" class="field-label">Experience level</label>
                <select id="{{ $uid }}-exp" name="experience" class="field">
                    @foreach (['1–3 years', '3–5 years', '5+ years', 'Team lead'] as $x)<option>{{ $x }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="{{ $uid }}-eng" class="field-label">Engagement model</label>
                <select id="{{ $uid }}-eng" name="engagement" class="field">
                    @foreach (['Full-time (160 hrs)', 'Part-time (80 hrs)', 'Hourly', 'Project-based'] as $x)<option>{{ $x }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="{{ $uid }}-start" class="field-label">When do you want to start?</label>
                <select id="{{ $uid }}-start" name="start_date" class="field">
                    @foreach (['Immediately', 'Within 2 weeks', 'Within a month', 'Just exploring'] as $x)<option>{{ $x }}</option>@endforeach
                </select>
            </div>
        </div>
    @endif

    @if ($variant !== 'compact')
        <div>
            <label for="{{ $uid }}-msg" class="field-label">Tell us about your goals <span class="font-normal text-muted">(optional)</span></label>
            <textarea id="{{ $uid }}-msg" name="message" rows="3" class="field" placeholder="E.g. We want more distributor enquiries from Gujarat and a new website.">{{ $val('message') }}</textarea>
        </div>
    @endif

    {{ $slot }}

    @if ($turnstileKey)
        <div class="cf-turnstile" data-sitekey="{{ $turnstileKey }}" data-size="flexible"></div>
        @once
            @push('scripts')<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>@endpush
        @endonce
    @endif

    <div>
        <label class="flex items-start gap-2.5 text-xs leading-relaxed {{ $dark ? 'text-white/70' : 'text-muted' }}">
            <input type="checkbox" name="consent" value="1" checked required class="mt-0.5 size-4 rounded border-line text-brand-600 focus:ring-brand-500">
            <span>I agree to be contacted by Advertally on call, WhatsApp or email about my enquiry. See our <a href="{{ route('privacy') }}" class="underline">privacy policy</a>.</span>
        </label>
        <p class="field-error" x-show="e('consent')" x-text="e('consent')">{{ $err('consent') }}</p>
    </div>

    <button type="submit" class="btn-cta w-full" :disabled="sending">
        <svg x-show="sending" x-cloak class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3"/></svg>
        <span x-text="sending ? 'Sending…' : @js($button)">{{ $button }}</span>
        <x-lucide name="arrow-right" class="size-4" x-show="!sending" />
    </button>
    <p class="flex items-center justify-center gap-1.5 text-center text-xs {{ $dark ? 'text-white/60' : 'text-muted' }}">
        <x-lucide name="lock" class="size-3.5" /> We reply within 2 working hours. Your details are never shared.
    </p>
</form>
