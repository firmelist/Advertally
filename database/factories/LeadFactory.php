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
        $services = fake()->randomElements(array_keys(Lead::SERVICE_OPTIONS), fake()->numberBetween(1, 3));

        return [
            'name' => fake()->name(),
            'company' => fake()->company(),
            'phone' => '9'.fake()->numerify('#########'),
            'email' => fake()->safeEmail(),
            'city' => fake()->randomElement(['Gurugram', 'Delhi', 'Noida', 'Mumbai', 'Pune', 'Bengaluru', 'Ahmedabad', 'Jaipur']),
            'business_size' => fake()->randomElement(['micro', 'small', 'small', 'medium']),
            'industry' => fake()->randomElement(array_keys(config('advertally.industries'))),
            'services' => $services,
            'budget' => fake()->randomElement(array_keys(config('advertally.budgets'))),
            'message' => fake()->sentence(12),
            'form_type' => fake()->randomElement(array_keys(Lead::FORM_TYPES)),
            'source_page' => '/',
            'utm_source' => fake()->randomElement(['google', 'facebook', 'instagram', 'linkedin', null, null]),
            'utm_medium' => fake()->randomElement(['cpc', 'organic', 'social', null]),
            'status' => fake()->randomElement(array_keys(config('advertally.lead_statuses'))),
            'score' => fake()->numberBetween(10, 90),
            'device' => fake()->randomElement(['mobile', 'mobile', 'desktop']),
            'consent' => true,
            'created_at' => fake()->dateTimeBetween('-45 days'),
        ];
    }
}
