<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'website' => fake()->domainName(),
            'industry' => fake()->randomElement(array_keys(config('advertally.industries'))),
            'service_interest' => fake()->randomElement(array_keys(config('advertally.service_interests'))),
            'budget' => fake()->randomElement(array_keys(config('advertally.budgets'))),
            'message' => fake()->sentence(12),
            'form_type' => 'contact',
            'source' => fake()->randomElement(['direct', 'organic:google', 'linkedin-ads', 'ai:chatgpt']),
            'status' => fake()->randomElement(array_keys(Lead::STATUSES)),
            'score' => fake()->numberBetween(10, 90),
            'consent' => true,
        ];
    }
}
