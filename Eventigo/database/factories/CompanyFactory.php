<?php

namespace Database\Factories;

use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        return [
            'name' => $name,
            'email' => fake()->companyEmail(),
            'slug' => Str::slug($name),
            'pricing_plan_id' => PricingPlan::inRandomOrder()->first()->id,
            'user_id' =>  User::factory(),
        ];
    }
}
