<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\tickets>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['Regular', 'VIP', 'Free']);
        $quantity_available = fake()->numberBetween(0, 500);

        return [
            'type' => $type,
            'price' => $type === 'Free' ? null : fake()->randomFloat(2),
            'description' => fake()->optional()->text(100),
            'quantity_available' => $quantity_available,
            'quantity_sold' => fake()->numberBetween(0, $quantity_available),
        ];
    }
}
