<?php

namespace Database\Seeders;

use App\Models\AuditDimension;
use Illuminate\Database\Seeder;

class ScoringSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('data/scoring.php');

        foreach ($data['growth_score'] as $i => $d) {
            $dimension = AuditDimension::query()->updateOrCreate(['type' => 'growth_score', 'key' => $d['key']], [
                'name' => $d['name'], 'description' => $d['description'], 'weight' => $d['weight'],
                'recommendations' => $d['recommendations'], 'sort_order' => $i,
            ]);

            if ($dimension->questions()->doesntExist()) {
                foreach ($d['questions'] as $q => $question) {
                    $dimension->questions()->create([
                        'question' => $question['question'],
                        'help_text' => $question['help'] ?? null,
                        'options' => $question['options'],
                        'sort_order' => $q,
                    ]);
                }
            }
        }

        foreach ($data['ai_visibility'] as $i => $d) {
            AuditDimension::query()->updateOrCreate(['type' => 'ai_visibility', 'key' => $d['key']], [
                'name' => $d['name'], 'description' => $d['description'], 'weight' => $d['weight'], 'sort_order' => $i,
            ]);
        }
    }
}
