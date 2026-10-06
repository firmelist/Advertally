<div x-data="cookieConsent" x-show="show" x-cloak x-transition.opacity
    class="fixed inset-x-3 bottom-3 z-50 sm:inset-x-auto sm:right-5 sm:bottom-5 sm:max-w-md" role="dialog" aria-live="polite" aria-label="Cookie preferences">
    <div class="card p-5 shadow-[var(--shadow-lift)]">
        <p class="text-sm font-semibold text-ink">Your privacy, your choice</p>
        <p class="mt-1.5 text-sm leading-relaxed text-muted">
            We use essential cookies to run this site and, with your permission, analytics cookies to understand what is useful.
            <a href="{{ url('cookie-policy') }}" class="font-semibold text-brand-700 hover:underline">Cookie policy</a>
        </p>
        <div class="mt-4 flex gap-2">
            <button type="button" class="btn-primary flex-1 !py-2.5" @click="choose('all')">Accept analytics</button>
            <button type="button" class="btn-secondary flex-1 !py-2.5" @click="choose('essential')">Essential only</button>
        </div>
    </div>
</div>
