<?php

namespace Database\Seeders;

use App\Models\Internship;
use Illuminate\Database\Seeder;

/**
 * Starter internships (database/data/internships.php). Only creates missing ones, so admin edits are never overwritten.
 */
class InternshipSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('data/internships.php') as $n => $data) {
            Internship::query()->firstOrCreate(['slug' => $data['slug']], [
                ...$data,
                'show_brands' => true, 'show_industries' => true, 'show_statistics' => false,
                'status' => 'published', 'sort_order' => $n,
            ]);
        }
    }
}
