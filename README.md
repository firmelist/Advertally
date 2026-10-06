# Advertally — AI-Native Growth & Revenue Partner

Website, CMS and lead engine for **advertally.com**, built with Laravel 12, Blade, Tailwind CSS 4, Alpine.js and Livewire 3 (via Filament 3 admin). Runs on plain PHP + MySQL shared hosting — **no Node.js in production**.

- **Architecture & content model:** [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- **Hostinger deployment:** [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)

## Run locally (XAMPP on Windows)

Requirements: PHP 8.2+, Composer, MySQL (XAMPP), Node 20+ (only for building CSS/JS).

```powershell
composer install
copy .env.example .env          # then set DB_DATABASE / DB_USERNAME / DB_PASSWORD
php artisan key:generate
php artisan migrate:fresh --seed   # creates tables + all launch content
php artisan storage:link
npm install
npm run build                   # or: npm run dev   (live reload while editing)
php artisan serve
```

- Website: http://localhost:8000
- Admin: http://localhost:8000/admin — `ADMIN_EMAIL` / `ADMIN_PASSWORD` from `.env`

Run the tests: `php artisan test`

## What is where

| Area | Location |
|---|---|
| Public routes | `routes/web.php` |
| Controllers / form validation | `app/Http/Controllers`, `app/Http/Requests` |
| Growth Score (Livewire) | `app/Livewire/GrowthScoreWizard.php` |
| AI Visibility Audit engine | `app/Services/Audit` |
| AI provider abstraction | `app/Services/AI` |
| SEO (meta + JSON-LD) | `app/Services/Seo.php`, `resources/views/partials/seo.blade.php` |
| Lead capture & attribution | `app/Services/LeadService.php`, `app/Services/Attribution.php` |
| Design system (tokens) | `resources/css/app.css` |
| Page sections (CMS blocks) | `resources/views/blocks/*` |
| Reusable components | `resources/views/components/*` |
| Admin (CMS + CRM) | `app/Filament` |
| Launch content | `database/data/*.php` (seeded; edit in the admin afterwards) |

## Content rules

No invented clients, testimonials, statistics, awards or guarantees. Demo figures are labelled **Sample data**; illustrative case studies are labelled **Sample case study** (`is_sample`). Testimonials and client logos stay hidden until real, approved ones are added in the admin.
