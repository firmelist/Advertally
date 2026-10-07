<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Upgrades the hero block of seeded pages to the animated visuals and gradient headlines.
 * Only touches a hero whose headline still matches the original seed, so admin edits are never overwritten.
 */
return new class extends Migration
{
    private const UPGRADES = [
        'home' => ['Make Your Business Impossible to Ignore.', 'Make Your Business *Impossible to Ignore.*', 'dashboard'],
        'about' => ['Marketing Changed. So Did We.', 'Marketing Changed. *So Did We.*', 'evolution'],
        'approach' => ['How Advertally Works.', 'How *Advertally* Works.', 'cycle'],
        'careers' => ['Do the best work of your career on growth that matters.', 'Do the best work of your career on *growth that matters.*', 'constellation'],
    ];

    public function up(): void
    {
        foreach (self::UPGRADES as $slug => [$from, $to, $visual]) {
            $page = DB::table('pages')->where('slug', $slug)->first();
            if (! $page) {
                continue;
            }

            $blocks = json_decode((string) $page->blocks, true) ?: [];
            $changed = false;

            foreach ($blocks as &$block) {
                if (($block['type'] ?? null) === 'hero' && ($block['data']['headline'] ?? null) === $from) {
                    $block['data']['headline'] = $to;
                    $block['data']['visual'] = $visual;
                    $changed = true;
                    break;
                }
            }
            unset($block);

            if ($changed) {
                DB::table('pages')->where('id', $page->id)->update(['blocks' => json_encode($blocks), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        // Content upgrade only; nothing to roll back structurally.
    }
};
