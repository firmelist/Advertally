import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

/**
 * Analytics helper — pushes events to GTM dataLayer / gtag / Meta Pixel when present.
 */
window.track = (event, params = {}) => {
    try {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ event, ...params });
        if (typeof window.gtag === 'function') window.gtag('event', event, params);
        if (typeof window.fbq === 'function' && event === 'generate_lead') window.fbq('track', 'Lead', params);
    } catch (e) { /* never break the page for analytics */ }
};

// Global click tracking for WhatsApp & call links
document.addEventListener('click', (e) => {
    const a = e.target.closest('a');
    if (!a) return;
    if (a.href.includes('wa.me/')) window.track('whatsapp_click', { page: location.pathname });
    if (a.href.startsWith('tel:')) window.track('call_click', { page: location.pathname });
});

/**
 * Exit-intent popup (desktop only, once per 7 days).
 */
Alpine.data('exitIntent', () => ({
    open: false,
    init() {
        if (window.matchMedia('(max-width: 1023px)').matches) return;
        if (document.cookie.includes('adv_exit=1')) return;
        const handler = (e) => {
            if (e.clientY <= 0) {
                this.open = true;
                document.cookie = 'adv_exit=1; max-age=' + 60 * 60 * 24 * 7 + '; path=/; SameSite=Lax';
                document.removeEventListener('mouseout', handler);
            }
        };
        setTimeout(() => document.addEventListener('mouseout', handler), 8000);
    },
}));

/**
 * Cookie consent.
 */
Alpine.data('cookieConsent', () => ({
    show: false,
    init() { this.show = !document.cookie.includes('adv_consent='); },
    choose(value) {
        document.cookie = 'adv_consent=' + value + '; max-age=' + 60 * 60 * 24 * 365 + '; path=/; SameSite=Lax';
        this.show = false;
        if (value === 'all') window.dispatchEvent(new Event('adv:consent'));
    },
}));

/**
 * "Build your own plan" pricing calculator.
 * items: [{key, label, group, price, unit}] ; billing discount applied client-side,
 * final selection is posted as a quote request (lead).
 */
Alpine.data('planBuilder', (items = [], discounts = {}) => ({
    items,
    discounts,
    billing: 'monthly',
    selected: [],
    toggle(key) {
        this.selected = this.selected.includes(key)
            ? this.selected.filter((k) => k !== key)
            : [...this.selected, key];
        if (this.selected.length === 1) window.track('pricing_calculator_used');
    },
    get monthly() {
        return this.items.filter((i) => this.selected.includes(i.key) && i.unit === 'month')
            .reduce((t, i) => t + i.price, 0);
    },
    get oneTime() {
        return this.items.filter((i) => this.selected.includes(i.key) && i.unit === 'one-time')
            .reduce((t, i) => t + i.price, 0);
    },
    get bundleDiscount() {
        // SME bundle: marketing + website + CRM together → extra 10% off monthly
        const groups = new Set(this.items.filter((i) => this.selected.includes(i.key)).map((i) => i.group));
        return groups.has('marketing') && groups.has('websites') && groups.has('crm') ? 0.10 : 0;
    },
    get effectiveMonthly() {
        const d = (this.discounts[this.billing] || 0) + this.bundleDiscount;
        return Math.round(this.monthly * (1 - d));
    },
    get summary() {
        return this.items.filter((i) => this.selected.includes(i.key)).map((i) => i.label).join(', ');
    },
    inr(n) { return '₹' + Number(n).toLocaleString('en-IN'); },
}));

/**
 * Marketing ROI calculator.
 */
Alpine.data('roiCalc', () => ({
    spend: 30000, cpl: 400, close: 10, order: 25000,
    get leads() { return Math.round(this.spend / Math.max(this.cpl, 1)); },
    get customers() { return Math.round(this.leads * this.close / 100); },
    get revenue() { return this.customers * this.order; },
    get roi() { return this.spend ? Math.round(((this.revenue - this.spend) / this.spend) * 100) : 0; },
    inr(n) { return '₹' + Number(n).toLocaleString('en-IN'); },
}));

window.Alpine = Alpine;
Alpine.start();
