<?php

use App\Http\Controllers\AiAuditController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GrowthScoreController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SolutionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

// Growth OS — the six engines and their services
Route::get('/solutions', [SolutionController::class, 'index'])->name('solutions.index');
Route::get('/solutions/{slug}', [SolutionController::class, 'show'])->name('solutions.show');
Route::get('/services/{slug}', [SolutionController::class, 'service'])->name('services.show');

// Growth Technology — the engine behind the system
Route::get('/growth-technology', [SolutionController::class, 'growthTechnology'])->name('growth-technology');
Route::get('/growth-technology/{slug}', [SolutionController::class, 'growthTechnologyService'])->name('growth-technology.show');

// Technology & Talent — separate commercial vertical with its own buyer journey
Route::get('/technology-talent', [SolutionController::class, 'talent'])->name('talent');
Route::get('/technology-talent/{slug}', [SolutionController::class, 'talentService'])->name('talent.show');

Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
Route::get('/industries/{slug}', [IndustryController::class, 'show'])->name('industries.show');

Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show'])->name('case-studies.show');

Route::get('/insights', [InsightController::class, 'index'])->name('insights.index');
Route::get('/insights/category/{slug}', [InsightController::class, 'category'])->name('insights.category');
Route::get('/insights/{slug}', [InsightController::class, 'show'])->name('insights.show');
Route::get('/authors/{slug}', [InsightController::class, 'author'])->name('authors.show');

Route::get('/ai-search-lab', [LabController::class, 'index'])->name('lab.index');
Route::get('/ai-search-lab/{slug}', [LabController::class, 'show'])->name('lab.show');

Route::get('/resources', [PageController::class, 'resources'])->name('resources');

// Internships
Route::get('/internships', [InternshipController::class, 'index'])->name('internships.index');
Route::get('/internships/{slug}', [InternshipController::class, 'show'])->name('internships.show');
Route::post('/internships/{slug}/apply', [InternshipController::class, 'apply'])->middleware('throttle:leads')->name('internships.apply');

// Signature products
Route::get('/growth-score', [GrowthScoreController::class, 'show'])->name('growth-score');
Route::get('/growth-score/report/{audit:uuid}', [GrowthScoreController::class, 'report'])->name('growth-score.report');

Route::get('/ai-visibility-audit', [AiAuditController::class, 'create'])->name('ai-audit');
Route::post('/ai-visibility-audit', [AiAuditController::class, 'store'])->middleware('throttle:audit')->name('ai-audit.store');
Route::get('/ai-visibility-audit/report/{audit:uuid}', [AiAuditController::class, 'report'])->name('ai-audit.report');

// Conversations
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:leads')->name('contact.store');
Route::get('/thank-you', [ContactController::class, 'thankYou'])->name('thank-you');

Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:leads')->name('newsletter.store');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Discovery
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('llms');

// CMS pages (about, approach, careers, legal …) — keep last.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', 'about|approach|careers|privacy-policy|terms|cookie-policy')
    ->name('page');
