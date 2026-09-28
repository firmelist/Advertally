<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Core pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/book-consultation', [PageController::class, 'consultation'])->name('consultation');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
Route::get('/thank-you', [PageController::class, 'thankYou'])->name('thank-you');
Route::get('/privacy-policy', [PageController::class, 'legal'])->defaults('page', 'privacy')->name('privacy');
Route::get('/terms', [PageController::class, 'legal'])->defaults('page', 'terms')->name('terms');
Route::get('/refund-policy', [PageController::class, 'legal'])->defaults('page', 'refund')->name('refund');

// Case studies
Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');
Route::get('/case-studies/{caseStudy}', [CaseStudyController::class, 'show'])->name('case-studies.show');

// Lead capture (all forms post here)
Route::post('/leads', [LeadController::class, 'store'])->middleware('throttle:leads')->name('leads.store');

// Free website audit tool
Route::get('/free-website-audit', [AuditController::class, 'create'])->name('audit.create');
Route::post('/free-website-audit', [AuditController::class, 'store'])->middleware('throttle:audit')->name('audit.store');
Route::get('/free-website-audit/report/{report}', [AuditController::class, 'show'])->name('audit.show');

// SEO
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Service hubs & child services — keep LAST so they never shadow the routes above.
$hubs = collect(config('advertally.ladder'))->pluck('slug')->all();

Route::get('/{hub}', [ServiceController::class, 'hub'])->whereIn('hub', $hubs)->name('services.hub');
Route::get('/{hub}/{service}', [ServiceController::class, 'show'])->whereIn('hub', $hubs)->where('service', '[a-z0-9-]+')->name('services.show');
