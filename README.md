# Advertally: website, lead engine and admin CRM

This is the Phase 1 (MVP) build of **advertally.com** in Laravel 12 with a Filament 3 admin panel.

Every page leads visitors up the **SME growth ladder**. Each rung is a service line, and each service page recommends the next one (the upsell path):

> **1. Grow** (Digital Marketing) → **2. Build** (Websites) → **3. Scale** (Hire Resources) → **4. Automate** (CRM & Automation) → **5. Transform** (Custom Software)

---

## 1. Brand colours

The colours were chosen for an IT and digital-marketing partner that sells to SME owners. They are design tokens in `resources/css/app.css` (`@theme`) and are repeated in `config/advertally.php`.

| Token | Hex | Why |
|---|---|---|
| **Trust Blue** (primary) | `#2952CC` | Blue signals trust, technology and reliability. This is the key purchase driver for owners handing their business to one partner. |
| **Deep Navy** (ink) | `#0B1B4D` | Headings and dark sections. Looks premium and corporate without being cold. |
| **Growth Orange** (CTA only) | `#F26B1D` | Energy and action. It sits opposite blue on the colour wheel, so CTAs stand out on every page. |
| **Tech Teal** (accent) | `#0F766E` | Used for automation and success states (check marks, "pass" results). |

Accessibility: orange buttons use navy text, not white, giving a **5.4:1 contrast ratio** (passes WCAG AA). White on orange would only reach 3.0:1.
Typography: **Plus Jakarta Sans** for headings and **Inter** for body text.

---

## 2. What's included (Phase 1)

**Public website**

- Home: hero, interactive growth ladder, problems we solve, tabbed services, results, industries, packages, process, testimonials, free tools (audit and ROI calculator), FAQ and a lead form
- 5 service hubs and 29 child service pages from one template: problem → solution → deliverables → process → pricing → case study → FAQ → **next-step upsell** → CTA
- Hire hub with engagement models, rate card and a "Request profiles" form
- Pricing page with monthly/quarterly/yearly toggle, category tabs and a **Build-your-own-plan calculator** (the SME Bundle gives an extra 10% off). The calculator result can be submitted as a quote.
- **Free Website Audit tool**. It checks SSL, speed, mobile, title/meta/H1, alt text, canonical, schema, robots, sitemap, Open Graph, WhatsApp/call/form and analytics. It shows a score and a report page, and emails a PDF.
- Case studies (list with filters, plus detail pages), About, Contact, Book consultation (Calendly), Thank-you, and Privacy/Terms/Refund templates (DPDP-aware)
- Site-wide: mega menu, floating WhatsApp button, sticky mobile Call/WhatsApp/Quote bar, exit-intent popup, cookie consent (analytics load only after consent)
- SEO: meta and Open Graph tags, canonical URLs, XML sitemap, robots.txt, and JSON-LD for Organization, Service, FAQPage, BreadcrumbList and Article

**Lead engine**

- All forms post to one endpoint and submit via AJAX, with inline errors and a no-JS fallback
- First-touch **UTM, gclid, fbclid, referrer and landing-page** capture, plus device and pages viewed
- **Lead scoring** from 0 to 100, tuned for small and medium businesses (see `app/Services/LeadScorer.php`)
- Round-robin assignment across active sales users
- Notifications (each channel fails on its own without blocking the others):
  - Email alert to sales
  - Auto-reply email to the prospect
  - WhatsApp alert and auto-reply (Cloud API templates)
  - In-app bell notification
  - Optional Slack alert
  - Optional outbound webhook (Zoho, HubSpot, Make or n8n)
- Spam protection: honeypot, time trap, rate limit (5/min and 20/day per IP) and optional Cloudflare Turnstile

**Admin panel (`/admin`)**

- **Dashboard:**
  - Leads today/week/month with trend
  - Win rate and open pipeline value
  - Follow-ups due
  - 30-day chart and source doughnut
  - "Call these first" list
  - **Upsell opportunities**: clients who haven't used the next rung of the ladder yet
- **Leads:**
  - Tabs: New, Hot, Follow-ups due, Open, Won
  - Filters, one-click WhatsApp and call, status change, activity logging
  - Bulk assign, bulk status change and CSV export
  - Activity timeline (status changes are logged automatically)
  - **Convert to client**
- **Pipeline board:** drag-and-drop Kanban
- **Clients:** track which ladder steps each client uses, MRR, and a filter for "not yet using X"
- **Audit reports**
- Website content: Services (tabs for basics, page content and SEO; reorderable), Pricing plans, Pricing-calculator items, Case studies, Testimonials, FAQs, Client logos
- Settings: site settings (phone, WhatsApp, address, stats, social links, GTM/GA4/Pixel/Clarity IDs), and Team & roles

**Roles**

| Role | Can access |
|---|---|
| Super Admin | Everything |
| Content Editor | Website content only |
| Sales Executive | **Only their own** leads, clients and audit reports |

---

## 3. Local setup

Requirements: PHP 8.2+ (with the `intl`, `gd`, `sqlite3`/`pdo_mysql`, `zip` and `mbstring` extensions), Composer 2, and Node 20+.

```bash
composer install
npm install && npm run build
composer run setup          # .env, key, SQLite DB, migrations + demo seed, storage link
php artisan serve           # http://localhost:8000
```

Admin: `http://localhost:8000/admin`. The password for all three demo users is the `ADMIN_PASSWORD` value in `.env` (default `ChangeMe@123`).

| Role | Email |
|---|---|
| Super Admin | admin@advertally.com |
| Sales Executive | sales@advertally.com |
| Content Editor | content@advertally.com |

Run the tests with `php artisan test` (45 tests covering public pages, lead capture, spam protection, UTM tracking, the audit tool, role access and admin actions).

To watch queued emails and notifications locally, run `php artisan queue:work`.

---

## 4. ⚠️ Replace before going live

The seeders contain **sample content so the design can be reviewed. It is not real data.** Before launch:

1. **Testimonials, case studies and client logos** (Admin → Website Content). Publish only genuine client stories that you have written permission to use.
2. **Stats**: years, clients, leads generated, retention and Google rating (Admin → Settings → Site settings).
3. **Contact details**: phone, WhatsApp number, email, address, map and GSTIN/Udyam number.
4. **Prices**: pricing plans and calculator items are suggested market-rate starting points. Adjust them to your real rate card.
5. **Legal pages**: `resources/views/pages/legal/*` are templates. Have them reviewed by your legal advisor.
6. `APP_ENV=production`, `APP_DEBUG=false`, and change `ADMIN_PASSWORD`, then change every user's password in the admin.
7. Add an Open Graph image at `public/images/og-default.png` (1200×630).

---

## 5. Integrations (`.env`)

| Variable | Purpose |
|---|---|
| `LEAD_ALERT_EMAILS` | Comma-separated emails for new-lead alerts |
| `MAIL_*` | SMTP settings (Brevo, SES, Zoho Mail and others) |
| `WHATSAPP_TOKEN`, `WHATSAPP_PHONE_NUMBER_ID`, `WHATSAPP_ALERT_TO` | Meta WhatsApp Cloud API. Create two **utility** templates: `new_lead_alert` with 5 variables (name, phone, company, services, score) and `lead_thank_you` with 1 variable (name). For Interakt, WATI or AiSensy, change the endpoint in `app/Services/WhatsApp.php`. |
| `PAGESPEED_API_KEY` | Adds the Google PageSpeed mobile score to the audit tool (free key from Google Cloud) |
| `TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY` | Cloudflare Turnstile spam protection (optional) |
| `LEAD_WEBHOOK_URL` | Every new lead is POSTed here as JSON (Zoho, HubSpot, Make or n8n) |
| `SLACK_LEAD_WEBHOOK` | Optional Slack alert |
| `BOOKING_URL` | Calendly or Google Calendar booking link |

Tracking IDs (GTM, GA4, Meta Pixel, Clarity) are set in **Admin → Settings → Site settings**. Events sent: `generate_lead`, `whatsapp_click`, `call_click` and `pricing_calculator_used`.

---

## 6. Deployment

### Shared hosting (cPanel)

1. Create a MySQL database and user. Upload the project **outside** `public_html`, for example to `/home/USER/advertally`.
2. Point the domain's document root to `/home/USER/advertally/public`. If you can't, copy the contents of `public/` into `public_html` and fix the two paths in `public_html/index.php`.
3. Run these in the cPanel terminal:
   ```bash
   composer install --no-dev --optimize-autoloader
   cp .env.example .env && php artisan key:generate   # then edit .env: APP_ENV=production, APP_DEBUG=false, APP_URL, DB_* (mysql), MAIL_*
   php artisan migrate --seed --force
   php artisan storage:link
   php artisan filament:optimize && php artisan optimize
   ```
4. Build assets locally with `npm run build` and upload `public/build/`.
5. Add **one cron job**: `* * * * * cd /home/USER/advertally && php artisan schedule:run >> /dev/null 2>&1`
   The scheduler drains the email/WhatsApp queue every minute (see `routes/console.php`).

### VPS (Ubuntu + Nginx + PHP-FPM)

- Nginx `root /var/www/advertally/public;` with `try_files $uri $uri/ /index.php?$query_string;`
- Run the queue worker under Supervisor: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`. Then remove the queue line from `routes/console.php` and keep the cron for `schedule:run`.
- Optionally switch `CACHE_STORE`, `SESSION_DRIVER` and `QUEUE_CONNECTION` to `redis`.
- Run `php artisan optimize` and `php artisan filament:optimize` on every deploy.

---

## 7. Content-editing guide (for the non-technical team)

- **Change a price:** Admin → Pricing Plans → edit → Save. Changes go live instantly.
- **Edit a service page:** Admin → Services → edit → "Page content" tab (pain points, solution text, what's included, process). Use the "SEO" tab for the Google title and description.
- **Add a new service:** Admin → Services → New. Pick the parent hub, and the growth-ladder step is set automatically.
- **Add a testimonial or case study:** Admin → Testimonials / Case Studies. Toggle "Live" to publish.
- **Phone, WhatsApp, address and stats:** Admin → Settings → Site settings (edit inline).
- **Work leads:** Admin → Leads → *New* tab. Use WhatsApp/Call, then "Log activity" and set the next follow-up. Drag cards on the Pipeline board as deals move. When a deal is won, open the lead and click **Convert to client**.
- **Upsell:** the dashboard's "Upsell opportunities" table lists which service to pitch next to each client.

---

## 8. Code map

```
app/
  Http/Controllers/        Page, Service, CaseStudy, Lead, Audit, Seo
  Http/Middleware/         CaptureAttribution (UTM), SecurityHeaders
  Http/Requests/           StoreLeadRequest (validation + honeypot + time-trap + Turnstile)
  Services/                LeadService, LeadScorer, WebsiteAuditor (SSRF-safe), WhatsApp, Turnstile
  Jobs/NotifyNewLead.php   email / WhatsApp / bell / Slack / webhook fan-out
  Filament/                Resources, Pipeline page, dashboard widgets, role trait
config/advertally.php      brand, growth ladder, dropdown options, integrations
database/data/             seed content (services, pricing, FAQs, sample social proof)
resources/views/
  layouts/ partials/       layout, header + mega menu, footer, floating actions
  components/              lead-form, growth-ladder, pricing-card, faq, testimonials, lucide icons…
  pages/ services/         page templates
```

## 9. Roadmap

- **Phase 2:** industry pages, programmatic city pages (`/digital-marketing-agency-in-{city}`), blog, portfolio, careers, proposal/quote builder with GST PDF, Razorpay checkout for starter plans
- **Phase 3:** product pages with trial signup, client portal (reports, invoices, tickets), referral/partner programme, Hindi version (`lang/` is ready)
