/*
 * Livewire ships Alpine with collapse, intersect, focus, anchor and mask already registered —
 * one runtime, no duplicate plugins.
 */
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

document.documentElement.classList.add('js');

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/** Analytics helper — pushes to GTM dataLayer / gtag when present. Never breaks the page. */
window.track = (event, params = {}) => {
    try {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ event, ...params });
        if (typeof window.gtag === 'function') window.gtag('event', event, params);
    } catch (e) { /* ignore */ }
};

document.addEventListener('click', (e) => {
    const el = e.target.closest('[data-track]');
    if (el) window.track(el.dataset.track, { label: el.dataset.trackLabel || el.textContent.trim().slice(0, 60), page: location.pathname });
});

/** Section reveals — progressive enhancement, skipped for reduced motion. */
const revealObserver = !reducedMotion && 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => entries.forEach((entry) => {
        if (entry.isIntersecting) { entry.target.classList.add('is-visible'); revealObserver.unobserve(entry.target); }
    }), { rootMargin: '0px 0px -8% 0px' })
    : null;

const initReveal = () => document.querySelectorAll('[data-reveal]:not(.is-visible)').forEach((el) => {
    revealObserver ? revealObserver.observe(el) : el.classList.add('is-visible');
});

document.addEventListener('DOMContentLoaded', initReveal);
document.addEventListener('livewire:navigated', initReveal);

/** Count-up metric: <span x-data="counter(428)" x-intersect.once="start()" x-text="display"> */
Alpine.data('counter', (target = 0, { decimals = 0, prefix = '', suffix = '', duration = 1400 } = {}) => ({
    display: prefix + Number(target).toLocaleString('en-IN', { maximumFractionDigits: decimals }) + suffix,
    start() {
        if (reducedMotion) return;
        const t0 = performance.now();
        const tick = (now) => {
            const p = Math.min(1, (now - t0) / duration);
            const eased = 1 - Math.pow(1 - p, 3);
            this.display = prefix + (target * eased).toLocaleString('en-IN', { maximumFractionDigits: decimals, minimumFractionDigits: p < 1 ? 0 : decimals }) + suffix;
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    },
}));

/** Score ring: animates stroke from 0 to the score. */
Alpine.data('scoreRing', (score = 0) => ({
    value: reducedMotion ? score : 0,
    start() {
        if (reducedMotion) return;
        requestAnimationFrame(() => { this.value = score; });
    },
}));

/** Desktop mega menu with hover-intent + keyboard support. */
Alpine.data('megaMenu', () => ({
    open: null,
    timer: null,
    show(id) { clearTimeout(this.timer); this.open = id; },
    hide() { this.timer = setTimeout(() => { this.open = null; }, 140); },
    toggle(id) { this.open = this.open === id ? null : id; },
    close() { this.open = null; },
}));

/** Cookie consent — analytics tags load only after "Accept". */
Alpine.data('cookieConsent', () => ({
    show: false,
    init() {
        const choice = (document.cookie.match(/(?:^|; )adv_consent=([^;]+)/) || [])[1];
        if (!choice) this.show = true;
        if (choice === 'all') window.dispatchEvent(new Event('adv:consent'));
    },
    choose(value) {
        document.cookie = `adv_consent=${value}; max-age=${60 * 60 * 24 * 365}; path=/; SameSite=Lax`;
        this.show = false;
        if (value === 'all') window.dispatchEvent(new Event('adv:consent'));
    },
}));

Livewire.start();
