<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Client;
use App\Models\ClientLogo;
use App\Models\Faq;
use App\Models\Lead;
use App\Models\PlanBuilderItem;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $content = require database_path('data/content.php');

        // ---- Users -------------------------------------------------------
        $admin = User::updateOrCreate(
            ['email' => config('advertally.admin.email')],
            ['name' => 'Advertally Admin', 'role' => 'admin', 'password' => config('advertally.admin.password')],
        );
        $sales = User::updateOrCreate(
            ['email' => 'sales@advertally.com'],
            ['name' => 'Priya Sharma', 'role' => 'sales', 'phone' => '+91 98100 00000', 'password' => config('advertally.admin.password')],
        );
        User::updateOrCreate(
            ['email' => 'content@advertally.com'],
            ['name' => 'Content Editor', 'role' => 'editor', 'password' => config('advertally.admin.password')],
        );

        // ---- Settings ----------------------------------------------------
        foreach ($content['settings'] as [$group, $key, $label, $value, $type]) {
            Setting::updateOrCreate(['key' => $key], compact('group', 'label', 'value', 'type'));
        }

        // ---- Services (hubs + children) ---------------------------------
        foreach (require database_path('data/services.php') as $h => $hub) {
            $children = $hub['children'] ?? [];
            unset($hub['children']);

            $parent = Service::updateOrCreate(
                ['slug' => $hub['slug'], 'parent_id' => null],
                [...$hub, 'sort_order' => $h + 1],
            );

            foreach ($children as $c => $child) {
                Service::updateOrCreate(
                    ['slug' => $child['slug'], 'parent_id' => $parent->id],
                    [
                        ...$child,
                        'pillar' => $parent->pillar,
                        'price_unit' => $child['price_unit'] ?? $parent->price_unit,
                        'hero_title' => $child['hero_title'] ?? $child['title'].' for growing businesses',
                        'hero_subtitle' => $child['hero_subtitle'] ?? $child['short_description'],
                        'meta_title' => $child['title'].' Company in India | Advertally',
                        'meta_description' => $child['short_description'],
                        'sort_order' => $c + 1,
                    ],
                );
            }
        }

        // Service-level FAQs (generic, editable)
        Service::query()->whereNotNull('parent_id')->each(function (Service $s) {
            $price = $s->starting_price ? inr($s->starting_price) : null;
            $faqs = [
                ["How much does {$s->title} cost?", $price
                    ? "{$s->title} starts at {$price}".($s->price_unit === 'month' ? ' per month' : ($s->price_unit === 'hour' ? ' per hour' : ' per project')).'. Final pricing depends on your scope — we share a fixed quote after a free consultation.'
                    : 'Pricing depends on your scope. We share a fixed quote after a free consultation.'],
                ['How do you report progress?', 'You get a dedicated account manager, a shared dashboard and a monthly report with a review call. For projects, we demo progress every two weeks.'],
                ['Can this work together with your other services?', 'Yes — that is our biggest advantage. Marketing, websites, CRM and automation are handled by one team, so everything is connected and measurable.'],
            ];
            foreach ($faqs as $i => [$q, $a]) {
                Faq::updateOrCreate(['service_id' => $s->id, 'question' => $q], ['answer' => $a, 'sort_order' => $i]);
            }
        });

        // ---- Pricing ------------------------------------------------------
        foreach ($content['pricing_plans'] as $i => $plan) {
            PricingPlan::updateOrCreate(
                ['category' => $plan['category'], 'name' => $plan['name']],
                [...$plan, 'sort_order' => $i],
            );
        }
        foreach ($content['plan_builder_items'] as $i => $item) {
            PlanBuilderItem::updateOrCreate(['label' => $item['label']], [...$item, 'sort_order' => $i]);
        }

        // ---- Social proof (SAMPLE content — replace before launch) --------
        foreach ($content['testimonials'] as $i => $t) {
            Testimonial::updateOrCreate(['name' => $t['name']], [...$t, 'sort_order' => $i]);
        }
        foreach ($content['faqs'] as $i => $f) {
            Faq::updateOrCreate(['page' => $f['page'], 'question' => $f['question']], [...$f, 'sort_order' => $i]);
        }
        foreach ($content['case_studies'] as $i => $cs) {
            CaseStudy::updateOrCreate(['slug' => $cs['slug']], [...$cs, 'sort_order' => $i]);
        }
        foreach ($content['client_logos'] as $i => $name) {
            ClientLogo::updateOrCreate(['name' => $name], ['sort_order' => $i]);
        }

        // ---- Demo CRM data (local only) ----------------------------------
        if (app()->environment('local') && Lead::count() === 0) {
            Lead::factory()->count(40)->create(['assigned_to' => fn () => fake()->randomElement([$admin->id, $sales->id, null])]);

            Client::create(['company' => 'SmileCare Dental Clinics', 'contact_name' => 'Dr. Ritu Malhotra', 'industry' => 'healthcare', 'business_size' => 'small', 'active_pillars' => ['grow'], 'monthly_value' => 24999, 'since' => now()->subMonths(8), 'account_manager_id' => $sales->id]);
            Client::create(['company' => 'Agarwal Polymers Pvt. Ltd.', 'contact_name' => 'Amit Agarwal', 'industry' => 'manufacturing', 'business_size' => 'medium', 'active_pillars' => ['grow', 'build'], 'monthly_value' => 59999, 'since' => now()->subYear(), 'account_manager_id' => $sales->id]);
            Client::create(['company' => 'Kaya Organics', 'contact_name' => 'Neha Kapoor', 'industry' => 'd2c', 'business_size' => 'small', 'active_pillars' => ['grow', 'build', 'automate'], 'monthly_value' => 44999, 'since' => now()->subMonths(5), 'account_manager_id' => $admin->id]);
        }

        Cache::forget('settings.all');
        Cache::forget('menu.hubs');
    }
}
