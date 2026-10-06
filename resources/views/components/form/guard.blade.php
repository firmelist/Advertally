{{-- Spam protection: honeypot (hidden from people and screen readers), time trap, optional Turnstile. --}}
<div class="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true">
    <label>Company URL <input type="text" name="company_url" tabindex="-1" autocomplete="off"></label>
</div>
<input type="hidden" name="_ts" value="{{ time() }}">
<input type="hidden" name="source_page" value="{{ url()->current() }}">
@if (config('advertally.turnstile.site_key'))
    <div class="cf-turnstile" data-sitekey="{{ config('advertally.turnstile.site_key') }}" data-theme="light"></div>
    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce
@endif
