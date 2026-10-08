<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Internships + permission-controlled brands/clients and client projects.
 * A brand or project reaches a public page only when it is public, approved for website display and active.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('logo')->nullable();
            $table->json('logo_variants')->nullable();     // {"160": path, "320": path} responsive sizes
            $table->string('website_url')->nullable();
            $table->string('industry', 100)->nullable()->index();
            $table->string('category', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('work_summary', 200)->nullable(); // "Digital Marketing / SEO / Performance Marketing"
            $table->boolean('is_public')->default(false);    // Public vs Private
            $table->string('display_permission', 20)->default('internal'); // approved | internal
            $table->boolean('show_on_internships')->default(true);
            $table->string('status', 20)->default('active')->index(); // active | inactive
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->string('department', 100)->nullable()->index();
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->string('location', 120)->nullable();
            $table->string('work_mode', 20)->default('hybrid'); // remote | hybrid | onsite
            $table->string('duration', 60)->nullable();
            $table->string('stipend', 80)->nullable();
            $table->string('openings', 40)->nullable();
            $table->string('start_date', 80)->nullable();
            $table->date('apply_by')->nullable();
            $table->string('hours', 80)->nullable();

            // Landing page content (each section hides itself when empty)
            $table->json('why')->nullable();               // [{title,text}]
            $table->text('team_intro')->nullable();        // "part of the team from day one"
            $table->json('team_points')->nullable();       // ["…"]
            $table->json('work_on')->nullable();           // [{title, items:[…]}]
            $table->json('toolkit')->nullable();           // ["Figma", "GA4", …]
            $table->json('skills')->nullable();            // ["…"]
            $table->json('learn')->nullable();             // ["…"]
            $table->json('who_should_apply')->nullable();  // ["…"]
            $table->json('requirements')->nullable();      // ["…"]
            $table->json('benefits')->nullable();          // [{title,text}] — what you get
            $table->json('journey')->nullable();           // [{title,text}]
            $table->text('career_growth')->nullable();
            $table->json('career_paths')->nullable();      // ["…"]
            $table->json('faqs')->nullable();

            // Brand section (per internship)
            $table->boolean('show_brands')->default(true);
            $table->string('brands_heading')->nullable();
            $table->string('brands_tagline')->nullable();
            $table->text('brands_description')->nullable();
            $table->json('exposure')->nullable();          // ["SEO", "Paid Ads", …] — "you may gain exposure to"
            $table->boolean('show_industries')->default(true);
            $table->boolean('show_statistics')->default(false);

            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('internship_brand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unique(['internship_id', 'brand_id']);
        });

        Schema::create('client_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 190);
            $table->text('description')->nullable();
            $table->string('industry', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->json('services')->nullable();
            $table->json('technologies')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_public')->default(false);
            $table->string('display_permission', 20)->default('internal');
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });

        Schema::create('internship_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_project_id')->constrained()->cascadeOnDelete();
            $table->text('intern_contribution')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 120);
            $table->string('email', 190)->index();
            $table->string('phone', 30)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('education', 190)->nullable();
            $table->string('graduation_year', 10)->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('resume_path')->nullable();       // private disk
            $table->string('resume_name')->nullable();
            $table->string('availability', 120)->nullable();
            $table->text('motivation')->nullable();
            $table->string('status', 20)->default('new')->index(); // new | shortlisted | interview | offered | rejected
            $table->text('notes')->nullable();
            $table->string('source_page', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // Dynamic statistics (e.g. "50+ Brands supported"). Nothing is hard-coded; empty until added in the admin.
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('context', 40)->default('internships')->index();
            $table->string('value', 40);
            $table->string('label', 120);
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // New permission areas: careers (internships, brands, projects, statistics) and applications (personal data).
        $now = now();
        foreach (['careers' => 'Internships, brands & projects', 'applications' => 'Internship applications'] as $area => $label) {
            foreach (['view' => 'View', 'manage' => 'Create & edit', 'delete' => 'Delete'] as $action => $actionLabel) {
                DB::table('permissions')->insertOrIgnore(['name' => "{$area}.{$action}", 'label' => "{$actionLabel} — {$label}", 'group' => $area, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
        if ($editor = DB::table('roles')->where('name', 'content-editor')->value('id')) {
            foreach (DB::table('permissions')->where('group', 'careers')->pluck('id') as $pid) {
                DB::table('permission_role')->insertOrIgnore(['permission_id' => $pid, 'role_id' => $editor]);
            }
        }
    }

    public function down(): void
    {
        foreach (['statistics', 'internship_applications', 'internship_project', 'client_projects', 'internship_brand', 'internships', 'brands'] as $table) {
            Schema::dropIfExists($table);
        }
        DB::table('permissions')->whereIn('group', ['careers', 'applications'])->delete();
    }
};
