<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Switches the homepage hero visual to the Growth OS Reactor — only if it is still on the seeded dashboard,
 * so a choice made in the admin is never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->swap('dashboard', 'growth-os');
    }

    public function down(): void
    {
        $this->swap('growth-os', 'dashboard');
    }

    private function swap(string $from, string $to): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();
        if (! $page) {
            return;
        }

        $blocks = json_decode((string) $page->blocks, true) ?: [];
        foreach ($blocks as &$block) {
            if (($block['type'] ?? null) === 'hero' && ($block['data']['visual'] ?? null) === $from) {
                $block['data']['visual'] = $to;
                DB::table('pages')->where('id', $page->id)->update(['blocks' => json_encode($blocks), 'updated_at' => now()]);
                break;
            }
        }
    }
};
