# Deploying Advertally to Hostinger (Premium/Business shared hosting)

The production server needs **PHP 8.2+, MySQL and Composer only**. CSS/JS are pre-built on your PC (`public/build` is committed to Git), so **no Node.js is required on the server**.

## 1. PHP requirements

In hPanel, go to **Advanced → PHP Configuration** and choose **PHP 8.2 or 8.3**. Enable these extensions: `pdo_mysql`, `mbstring`, `intl`, `fileinfo`, `openssl`, `curl`, `dom`, `zip`, `bcmath`. Set `memory_limit` to at least 256M.

## 2. Build assets on your PC

```powershell
npm install
npm run build
git add public/build
git commit -m "Build assets"
```

## 3. Upload the code

The recommended layout keeps the code outside the web root:

```
/home/uXXXX/domains/advertally.com/
├── advertally/        ← the whole project (git clone or upload)
└── public_html/       ← contents of advertally/public/
```

- **With SSH (recommended):** `git clone https://github.com/firmelist/Advertally.git advertally`, then run `composer install --no-dev --optimize-autoloader` inside `advertally/`.
- **Without SSH:** run `composer install --no-dev --optimize-autoloader` on your PC, zip the project (including `vendor/` and `public/build/`, but **not** `node_modules/` or `.env`), upload it via File Manager and extract.

Copy the **contents** of `advertally/public/` into `public_html/`, then edit `public_html/index.php`:

```php
if (file_exists($maintenance = __DIR__.'/../advertally/storage/framework/maintenance.php')) { require $maintenance; }
require __DIR__.'/../advertally/vendor/autoload.php';
$app = require_once __DIR__.'/../advertally/bootstrap/app.php';
```

Every time you rebuild assets, copy `advertally/public/build` to `public_html/build` again.

## 4. Database

In hPanel, open **Databases → MySQL Databases** and create the database and user. Note the full names (e.g. `u123456789_advertally`).

## 5. Environment (`advertally/.env`)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://advertally.com
APP_KEY=            # php artisan key:generate

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u123456789_advertally
DB_USERNAME=u123456789_advertally
DB_PASSWORD="your-db-password"

SESSION_SECURE_COOKIE=true
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=hello@advertally.com
MAIL_PASSWORD="mailbox-password"
MAIL_FROM_ADDRESS=hello@advertally.com

ADMIN_EMAIL=you@advertally.com
ADMIN_PASSWORD="a-long-unique-password"
LEAD_ALERT_EMAILS=you@advertally.com

# Analytics IDs (optional) — GTM_ID, GA4_MEASUREMENT_ID, META_PIXEL_ID, LINKEDIN_PARTNER_ID, CLARITY_PROJECT_ID, GOOGLE_SITE_VERIFICATION
# AI (optional) — AI_PROVIDER=openai|anthropic|gemini plus the matching *_API_KEY
```

Quote any value that contains `#`, spaces or `@`. Never place `.env` inside `public_html`.

## 6. First-time setup (SSH)

```bash
cd ~/domains/advertally.com/advertally
php artisan key:generate --force
php artisan migrate --seed --force
php artisan storage:link        # creates advertally/public/storage
ln -s ~/domains/advertally.com/advertally/storage/app/public ~/domains/advertally.com/public_html/storage
php artisan filament:assets
cp -r public/css public/js ~/domains/advertally.com/public_html/   # Filament admin assets
php artisan optimize
chmod -R 775 storage bootstrap/cache
```

Without SSH, ask Hostinger support to run these commands, or use a plan that includes SSH.

## 7. Cron (scheduler + queue)

In hPanel, open **Advanced → Cron Jobs** and add a job that runs **every minute**:

```
cd /home/uXXXX/domains/advertally.com/advertally && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

The scheduler works through the database queue every minute (lead emails, Slack and webhooks), so shared hosting needs no queue daemon.

## 8. Caching

`php artisan optimize` caches config, routes, views and events. Run `php artisan optimize:clear && php artisan optimize` after every deploy or `.env` change. The sitemap and `llms.txt` refresh automatically when content is saved. Redis is not required; the cache uses the database.

## 9. Updating the site

```bash
cd ~/domains/advertally.com/advertally
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
cp -r public/build ~/domains/advertally.com/public_html/
php artisan optimize:clear && php artisan optimize
php artisan up
```

**Do not** run `migrate:fresh` or `db:seed` on production after launch. It resets content edited in the admin. To add new seed content safely, run one seeder, e.g. `php artisan db:seed --class=ScoringSeeder --force`.

## 10. After launch

1. Sign in at `/admin` and change the admin password (profile menu).
2. Fill in **Site → Settings**: phone, address, LinkedIn and other profiles. These feed Organization schema.
3. Replace or unpublish the two **sample case studies** once real, approved stories exist.
4. Add real testimonials and client logos (both stay hidden until added).
5. Submit `https://advertally.com/sitemap.xml` in Google Search Console and Bing Webmaster Tools.
6. Check `/robots.txt`. It allows crawling only when `APP_ENV=production`.
