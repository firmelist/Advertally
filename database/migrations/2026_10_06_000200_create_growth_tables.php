<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Revenue side: leads, raw form submissions, newsletter, Growth Score and AI Visibility audits.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('company', 190)->nullable();
            $table->string('email', 190)->index();
            $table->string('phone', 30)->nullable();
            $table->string('website')->nullable();
            $table->string('job_title', 120)->nullable();
            $table->string('industry', 60)->nullable()->index();
            $table->string('service_interest', 60)->nullable()->index();
            $table->string('challenge', 60)->nullable();
            $table->string('objective', 60)->nullable();
            $table->string('budget', 30)->nullable();
            $table->text('message')->nullable();

            // Attribution
            $table->string('form_type', 30)->default('contact')->index(); // contact | growth_score | ai_audit | talent | newsletter
            $table->string('source', 60)->nullable()->index();             // channel: google / linkedin / direct / referral:domain …
            $table->string('landing_page', 500)->nullable();
            $table->string('source_page', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('utm_source', 100)->nullable()->index();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 150)->nullable()->index();
            $table->string('utm_term', 150)->nullable();
            $table->string('utm_content', 150)->nullable();
            $table->string('first_touch_source', 100)->nullable();
            $table->string('first_touch_medium', 100)->nullable();
            $table->string('first_touch_campaign', 150)->nullable();
            $table->string('first_touch_landing_page', 500)->nullable();
            $table->timestamp('first_touch_at')->nullable();
            $table->string('last_touch_source', 100)->nullable();
            $table->string('gclid')->nullable();
            $table->string('fbclid')->nullable();
            $table->string('li_fat_id')->nullable();
            $table->string('device', 20)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            // Pipeline
            $table->string('status', 20)->default('new')->index();
            $table->unsignedTinyInteger('score')->default(0);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('estimated_value')->nullable();
            $table->timestamp('next_follow_up_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->default('note'); // note | call | email | meeting | status
            $table->text('body');
            $table->timestamps();
        });

        // Every raw form post, kept verbatim for audit even if the lead is later edited/merged.
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('form', 40)->index();
            $table->json('payload');
            $table->string('page_url', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 190)->unique();
            $table->string('name', 120)->nullable();
            $table->string('source', 120)->nullable();
            $table->string('status', 20)->default('subscribed')->index(); // subscribed | unsubscribed
            $table->string('token', 64)->unique();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        // Catalogue of scoring dimensions for both products (growth_score | ai_visibility).
        Schema::create('audit_dimensions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->index();
            $table->string('key', 40);
            $table->string('name', 80);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('weight')->default(1);
            $table->json('recommendations')->nullable(); // [{below:int, title, description, impact}]
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['type', 'key']);
        });

        Schema::create('growth_score_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_dimension_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->string('help_text')->nullable();
            $table->json('options'); // [{label, points 0-100}]
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('audit_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type', 20)->index();           // growth_score | ai_visibility
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 120)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('company', 190)->nullable();
            $table->string('website')->nullable();
            $table->string('industry', 60)->nullable();
            $table->string('country', 60)->nullable();
            $table->string('primary_service', 150)->nullable();
            $table->string('competitor')->nullable();
            $table->json('answers')->nullable();            // growth score questionnaire
            $table->string('status', 20)->default('pending')->index(); // pending | processing | completed | needs_review | failed
            $table->unsignedTinyInteger('overall_score')->nullable();
            $table->string('engine', 40)->nullable();       // onsite-signals | questionnaire | openai | anthropic …
            $table->json('signals')->nullable();            // raw analyser output
            $table->text('summary')->nullable();
            $table->text('error')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('audit_dimension_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->text('summary')->nullable();
            $table->json('checks')->nullable(); // [{label,status,detail}]
            $table->unique(['audit_request_id', 'audit_dimension_id']);
        });

        Schema::create('audit_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('audit_dimension_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('impact', 10)->default('medium'); // high | medium | low
            $table->unsignedSmallInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        foreach (['audit_recommendations', 'audit_scores', 'audit_requests', 'growth_score_questions', 'audit_dimensions',
            'newsletter_subscribers', 'contact_submissions', 'lead_activities', 'leads'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
