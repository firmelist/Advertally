<?php

use App\Models\NavigationItem;
use Database\Seeders\InternshipSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Brings existing installs up to date: starter internships, "Internships" links under Company (header + footer)
 * and a link from the Careers hero. Each step is skipped if it was already done or changed in the admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('internships')->doesntExist()) {
            (new InternshipSeeder)->run();
        }

        if (DB::table('navigation_items')->where('url', '/internships')->doesntExist()) {
            foreach (['header', 'footer'] as $menu) {
                $careers = DB::table('navigation_items')->where('menu', $menu)->where('url', '/careers')->whereNotNull('parent_id')->first();
                if ($careers) {
                    DB::table('navigation_items')->where('parent_id', $careers->parent_id)->where('sort_order', '>', $careers->sort_order)->increment('sort_order');
                    DB::table('navigation_items')->insert([
                        'menu' => $menu, 'parent_id' => $careers->parent_id, 'label' => 'Internships', 'url' => '/internships',
                        'description' => $menu === 'header' ? 'Learn by working on real growth projects.' : null,
                        'icon' => $menu === 'header' ? 'book' : null,
                        'is_active' => true, 'sort_order' => $careers->sort_order + 1, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
            NavigationItem::flushCache();
        }

        $page = DB::table('pages')->where('slug', 'careers')->first();
        if ($page) {
            $blocks = json_decode((string) $page->blocks, true) ?: [];
            foreach ($blocks as &$block) {
                if (($block['type'] ?? null) === 'hero' && empty($block['data']['secondary_label'])) {
                    $block['data']['secondary_label'] = 'View internships';
                    $block['data']['secondary_url'] = '/internships';
                    DB::table('pages')->where('id', $page->id)->update(['blocks' => json_encode($blocks), 'updated_at' => now()]);
                    break;
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('navigation_items')->where('url', '/internships')->delete();
        NavigationItem::flushCache();
    }
};
