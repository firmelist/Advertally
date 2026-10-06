<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Public website content: solutions, services, industries, case studies, insights, AI Search Lab, pages.
 */
return new class extends Migration
{
    public function up(): void
    {
        // The six Growth OS engines (group "solution") plus the Growth Technology and Technology & Talent verticals.
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('group', 20)->default('solution')->index(); // solution | technology | talent
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->string('number', 4)->nullable();           // "01"
            $table->string('tagline', 120)->nullable();        // "Be Found."
            $table->string('headline')->nullable();            // page H1
            $table->text('subheadline')->nullable();
            $table->text('summary')->nullable();               // card copy
            $table->longText('intro')->nullable();             // HTML
            $table->string('icon', 60)->nullable();
            $table->string('flow_title')->nullable();
            $table->json('flow')->nullable();                  // ["Ad Spend","Qualified Traffic",…]
            $table->string('metrics_title')->nullable();
            $table->json('metrics')->nullable();               // ["Qualified Leads","Pipeline",…]
            $table->json('capabilities')->nullable();          // chips
            $table->json('highlights')->nullable();            // [{title,text}]
            $table->json('process')->nullable();               // [{title,text}] default for child services
            $table->json('faqs')->nullable();                  // [{question,answer}]
            $table->text('principle')->nullable();             // positioning quote
            $table->string('cta_label', 80)->nullable();
            $table->string('cta_url')->nullable();
            $table->string('status', 20)->default('published')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->string('short_description', 300)->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->longText('long_description')->nullable(); // HTML
            $table->json('benefits')->nullable();             // [{title,text}]
            $table->json('deliverables')->nullable();         // ["…"]
            $table->json('process')->nullable();              // [{title,text}] overrides category process
            $table->json('faqs')->nullable();                 // [{question,answer}]
            $table->string('icon', 60)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('published')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_related', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_service_id')->constrained('services')->cascadeOnDelete();
            $table->primary(['service_id', 'related_service_id']);
        });

        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->text('how_customers_search')->nullable();
            $table->text('ai_discovery')->nullable();
            $table->json('challenges')->nullable();      // ["…"]
            $table->json('opportunities')->nullable();   // [{title,text}]
            $table->json('growth_system')->nullable();   // [{title,text}]
            $table->json('faqs')->nullable();
            $table->string('icon', 60)->nullable();
            $table->string('status', 20)->default('published')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('industry_service', function (Blueprint $table) {
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['industry_id', 'service_id']);
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->default('person'); // person | team
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->string('job_title', 150)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->json('expertise')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('x_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('role', 150)->nullable();
            $table->string('company', 150)->nullable();
            $table->text('quote');
            $table->string('photo')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('client_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('logo');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('testimonial_id')->nullable()->constrained()->nullOnDelete();
            $table->string('client_name', 150);
            $table->boolean('is_sample')->default(false); // illustrative, not a real client — always labelled publicly
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('challenge')->nullable();
            $table->longText('diagnosis')->nullable();
            $table->longText('strategy')->nullable();
            $table->longText('execution')->nullable();
            $table->longText('technology')->nullable();
            $table->longText('results')->nullable();
            $table->longText('business_impact')->nullable();
            $table->json('chart')->nullable(); // {title, unit, labels:[…], values:[…]}
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('case_study_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_study_id')->constrained()->cascadeOnDelete();
            $table->string('label', 120);
            $table->string('value', 40);           // "+182%"
            $table->string('before', 40)->nullable();
            $table->string('after', 40)->nullable();
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('case_study_service', function (Blueprint $table) {
            $table->foreignId('case_study_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['case_study_id', 'service_id']);
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->default('article')->index(); // article | report | framework | research | guide
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedSmallInteger('reading_time')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('post_service', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'service_id']);
        });

        Schema::create('industry_post', function (Blueprint $table) {
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->primary(['industry_id', 'post_id']);
        });

        Schema::create('ai_research', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category', 60)->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->json('key_findings')->nullable();  // ["…"]
            $table->longText('body')->nullable();
            $table->longText('methodology')->nullable();
            $table->json('data')->nullable();          // {columns:[…], rows:[[…]]}
            $table->json('charts')->nullable();        // [{title, unit, labels:[…], values:[…]}]
            $table->json('sources')->nullable();       // [{title,url}]
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('ai_research_service', function (Blueprint $table) {
            $table->foreignId('ai_research_id')->constrained('ai_research')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['ai_research_id', 'service_id']);
        });

        // Block-based pages (home, about, approach, careers, legal…). Each block maps to a Blade section component.
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('template', 30)->default('blocks'); // blocks | legal
            $table->json('blocks')->nullable();
            $table->longText('body')->nullable();
            $table->string('status', 20)->default('published')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['pages', 'ai_research_service', 'ai_research', 'industry_post', 'post_service', 'posts', 'post_categories',
            'case_study_service', 'case_study_metrics', 'case_studies', 'client_logos', 'testimonials', 'authors',
            'industry_service', 'industries', 'service_related', 'services', 'service_categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
