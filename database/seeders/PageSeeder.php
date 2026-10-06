<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('data/pages.php') as $data) {
            $page = Page::query()->updateOrCreate(['slug' => $data['slug']], [
                'title' => $data['title'],
                'template' => $data['template'] ?? 'blocks',
                'blocks' => $data['blocks'] ?? [],
                'body' => $data['body'] ?? null,
                'status' => 'published',
            ]);

            if (! empty($data['seo'])) {
                $page->seo()->updateOrCreate([], $data['seo']);
            }
        }
    }
}
