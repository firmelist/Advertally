<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Services: top-level "hubs" (one per growth-ladder pillar) and child services.
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('pillar', 20)->index(); // grow|build|scale|automate|transform
            $table->string('title');
            $table->string('slug');
            $table->string('icon', 40)->default('sparkles');
            $table->string('short_description', 300)->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->json('problems')->nullable();      // ["pain point", ...]
            $table->longText('body')->nullable();      // rich text "our solution"
            $table->json('deliverables')->nullable();  // ["deliverable", ...]
            $table->json('process')->nullable();       // [{title, text}]
            $table->json('rate_card')->nullable();     // hire hub: [{model, price, unit, note}]
            $table->unsignedInteger('starting_price')->nullable();
            $table->string('price_unit', 20)->default('month'); // month|project|hour
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['parent_id', 'slug']);
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20)->index(); // marketing|websites|hire|crm|maintenance
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('audience', 20)->nullable(); // micro|small|medium
            $table->unsignedInteger('price');
            $table->string('price_unit', 20)->default('month'); // month|one-time|hour
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->string('cta_label', 40)->default('Get Started');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // "Build your own plan" line items used by the pricing calculator.
        Schema::create('plan_builder_items', function (Blueprint $table) {
            $table->id();
            $table->string('group', 20); // marketing|websites|hire|crm
            $table->string('label');
            $table->unsignedInteger('price');
            $table->string('unit', 20)->default('month'); // month|one-time
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('company')->nullable();
            $table->string('city')->nullable();
            $table->string('industry', 40)->nullable();
            $table->text('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('photo')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('page', 40)->nullable()->index(); // home|pricing|hire|audit|contact
            $table->string('question');
            $table->text('answer');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client');
            $table->string('industry', 40)->nullable();
            $table->string('city')->nullable();
            $table->json('services')->nullable();  // ["SEO", "Google Ads"]
            $table->string('summary', 300)->nullable();
            $table->text('challenge')->nullable();
            $table->text('solution')->nullable();
            $table->json('results')->nullable();   // [{value: "+212%", label: "Leads in 90 days"}]
            $table->text('testimonial')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('client_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 40)->default('general');
            $table->string('key')->unique();
            $table->string('label')->nullable();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('text'); // text|textarea|url|email|number|boolean
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('client_logos');
        Schema::dropIfExists('case_studies');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('plan_builder_items');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('services');
    }
};
