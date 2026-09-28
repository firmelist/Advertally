<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('phone', 20)->index();
            $table->string('email')->nullable()->index();
            $table->string('city', 80)->nullable();
            $table->string('website')->nullable();
            $table->string('business_size', 20)->nullable();  // micro|small|medium|large
            $table->string('industry', 40)->nullable();
            $table->json('services')->nullable();              // ["seo", "website", ...]
            $table->string('budget', 20)->nullable();
            $table->text('message')->nullable();

            // Where it came from
            $table->string('form_type', 30)->default('contact')->index(); // contact|quote|audit|hire|plan_builder|popup|consultation
            $table->string('source_page', 500)->nullable();
            $table->string('landing_page', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('utm_source', 100)->nullable()->index();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 150)->nullable();
            $table->string('utm_term', 150)->nullable();
            $table->string('utm_content', 150)->nullable();
            $table->string('gclid', 255)->nullable();
            $table->string('fbclid', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->unsignedSmallInteger('pages_viewed')->default(0);

            // Pipeline
            $table->string('status', 20)->default('new')->index();
            $table->unsignedTinyInteger('score')->default(0)->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('deal_value')->nullable();
            $table->timestamp('next_follow_up_at')->nullable()->index();
            $table->timestamp('contacted_at')->nullable();
            $table->string('lost_reason')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->default('note'); // note|call|whatsapp|email|meeting|status
            $table->text('body');
            $table->timestamps();
        });

        // Won leads become clients. Tracks which ladder services they use → upsell engine.
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('company');
            $table->string('contact_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('industry', 40)->nullable();
            $table->string('business_size', 20)->nullable();
            $table->json('active_pillars')->nullable(); // ["grow","build"]
            $table->unsignedInteger('monthly_value')->default(0);
            $table->date('since')->nullable();
            $table->string('status', 20)->default('active'); // active|paused|churned
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('url', 500);
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedTinyInteger('performance_score')->nullable();
            $table->json('checks')->nullable();
            $table->string('uuid', 36)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_reports');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('leads');
    }
};
