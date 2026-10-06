# Advertally — Architecture

## 1. Positioning hierarchy

| Level | Offer | URL space |
|---|---|---|
| **Primary** | Advertally Growth — six engines (Growth OS) | `/solutions/*`, `/services/*` |
| Secondary capability | Growth Technology — the engine behind growth | `/growth-technology/*` |
| Secondary vertical | Technology & Talent — specialists and dedicated teams | `/technology-talent/*` (own enquiry form, `form_type = talent`) |

The six engines: **01 AI Search** (Be Found) · **02 Demand** (Be Discovered) · **03 Authority** (Be Trusted) · **04 Conversion** (Be Chosen) · **05 Automation** (Scale Faster) · **06 Intelligence** (Know What Works).

One primary CTA site-wide — **Get Your Growth Score →** — and one secondary — **Talk to an Expert**.

## 2. Sitemap

```
/                                   Home (CMS blocks)
/solutions                          Growth OS overview
/solutions/{ai-search|demand|authority|conversion|automation|intelligence}
/services/{slug}                    31 engine services (seo, geo, aeo, google-ads, abm, cro, ai-agents, attribution …)
/growth-technology                  + /websites /ecommerce /crm-integration /api-integration /ai-integration /custom-growth-tools /dashboards
/technology-talent                  + /hire-developers /hire-designers /hire-seo-specialists /hire-digital-marketers /dedicated-teams
/industries                         + /technology /saas /consulting /professional-services /financial-services /recruitment /b2b-services
/case-studies                       + /{slug}  (Growth Stories)
/insights                           + /category/{slug}, /{slug}, ?type=report|framework|guide
/authors/{slug}
/ai-search-lab                      + /{slug}  (research publication)
/resources
/growth-score                       Livewire questionnaire → /growth-score/report/{uuid}
/ai-visibility-audit                Form → /ai-visibility-audit/report/{uuid}
/about  /approach  /careers  /contact  /thank-you
/privacy-policy  /terms  /cookie-policy
/sitemap.xml  /robots.txt  /llms.txt
/admin                              Filament CMS + CRM
```

## 3. Database (MySQL)

| Domain | Tables |
|---|---|
| Access | `users` (role_id), `roles`, `permissions`, `permission_role`, `activity_logs` |
| Solutions | `service_categories` (group: solution / technology / talent), `services`, `service_related`, `industry_service` |
| Content | `industries`, `case_studies`, `case_study_metrics`, `case_study_service`, `testimonials`, `client_logos`, `posts`, `post_categories`, `post_service`, `industry_post`, `authors`, `ai_research`, `ai_research_service`, `pages` (block JSON) |
| Growth | `leads`, `lead_activities`, `contact_submissions`, `newsletter_subscribers` |
| Scoring | `audit_dimensions`, `growth_score_questions`, `audit_requests`, `audit_scores`, `audit_recommendations` |
| Site | `settings`, `navigation_items` (3 levels), `seo_metadata` (polymorphic), `media` |

**Lead attribution:** first touch (90-day cookie) and last touch (session) — `utm_*`, `gclid`/`fbclid`/`li_fat_id`, referrer, landing page and a derived channel such as `organic:google`, `ai:chatgpt`, `linkedin-ads` or `referral:domain`.

## 4. Front end

- **Blade + Tailwind 4 + Alpine** (Alpine comes bundled with Livewire, so there is one runtime). JS ≈ 66 KB gzip, CSS ≈ 16 KB gzip.
- **Design tokens** in `resources/css/app.css`: brand blue `#2563EB`, deep navy `#0B1F3A`, warm white `#F8FAFC`, AI violet `#7C3AED` (sparingly), signal cyan `#06B6D4` (data), growth green `#16A34A` (positive outcomes only). Typeface: Plus Jakarta Sans.
- **Signature visual:** `<x-signal>` — *The Advertally Signal™* (Intent → Search → AI → Authority → Demand → Conversion → Revenue). Used in the hero dashboard, the Signal band, Solutions, the footer, the 404 page and the OG image.
- **Components:** `glyph` (icons), `logo`, `signal`, `growth-dashboard`, `score-ring`, `section-heading`, `cta-buttons`, `cta-band`, `faq`, `breadcrumbs`, `cards/*`, `form/*`.
- **CMS blocks** (`resources/views/blocks`): hero, trust, journey, story, growth_os, signal, technology, score, case_studies, insights, testimonials, talent, industries, steps, comparison, features, equation, rich_text, faq, cta.
- Accessibility: semantic landmarks, skip link, one H1 per page, keyboard-operable menus and tabs, focus states, `prefers-reduced-motion`, labelled forms.

## 5. CMS / admin (Filament)

Sidebar: **Growth** (Leads, Audit requests, Form submissions, Newsletter, Analytics) · **Website Content** (Pages, Solutions, Services, Industries, Case studies, Insights, AI Search Lab, Categories, Authors, Testimonials, Client logos, Media) · **Growth Score** (dimensions, questions, recommendation rules) · **Site** (Navigation, SEO overrides, Settings) · **System** (Users, Roles & permissions, Activity log).

Roles (seeded): **Super Admin**, **Content Editor** (content/SEO/media, no leads), **Growth Consultant** (only their assigned leads; round-robin assignment), **Analyst** (read-only). Permissions follow `{area}.view|manage|delete` and are enforced by policies in `app/Policies`.

## 6. Scoring products

- **Growth Score™** — 12 questions across six dimensions (AI Visibility, Search Visibility, Authority, Demand, Conversion, Intelligence). Points live server-side; dimension score = average; overall = weighted average; recommendations come from per-dimension threshold rules. Questions and rules are editable in the admin.
- **AI Visibility Audit** — `AiVisibilityAnalyzer` fetches the homepage, `robots.txt`, `llms.txt` and `sitemap.xml` (SSRF-safe) and scores six dimensions: AI crawler access, server-rendered content, schema/entity, authority signals, content coverage and conversion readiness. Sites that block bots go to **Needs analyst review** instead of failing. It never claims to measure live AI citations.

## 7. AI architecture

`App\Services\AI\AiProvider` interface → `OpenAiProvider`, `AnthropicProvider`, `GeminiProvider`, `NullProvider`, resolved by `AiManager` from `AI_PROVIDER`. Keys are read server-side only. It is currently used for report executive summaries, with a rule-based fallback. Future features (AI Visibility Monitor, lead summariser, reporting agent, website assistant) should depend on the interface, never on a vendor SDK.

## 8. SEO / GEO

Every page: title, description, canonical, robots, Open Graph and Twitter tags, plus one JSON-LD `@graph` (Organization + WebSite + BreadcrumbList + page nodes: Service, FAQPage, Article/Report, ScholarlyArticle, Person/Organization authors). Per-record overrides live in `seo_metadata`. `sitemap.xml` and `llms.txt` are generated from the database and cached; they are cleared automatically whenever content is saved. Internal linking: service ↔ related services ↔ industries ↔ case studies ↔ insights ↔ research.

## 9. Security

CSRF, Eloquent/Form Request validation, HTML sanitised on output (`sanitizeHtml`), rate limits (`leads`, `audit`, Growth Score), honeypot + time trap + optional Turnstile, SSRF protection on outbound audit fetches, upload MIME/size limits, hashed passwords with strength rules, role/permission policies, an activity log of admin changes and logins, security headers, HTTPS forced in production, and secrets kept in `.env` only.
