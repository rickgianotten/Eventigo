<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $orderStatus = OrderStatus::random();
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'total_price' => fake()->numberBetween(1, 150),
            'payment_status' => $orderStatus,
            'paid_at' => $orderStatus == OrderStatus::Paid ? now() : null,
        ];
    }
}
