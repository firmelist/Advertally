<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccessSeeder::class,
            SettingSeeder::class,
            ContentSeeder::class,
            ScoringSeeder::class,
            PageSeeder::class,
            NavigationSeeder::class,
            SampleCaseStudySeeder::class,
            InternshipSeeder::class,
        ]);
    }
}
