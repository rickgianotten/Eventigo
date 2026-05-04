<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\events>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventImages =  File::files(public_path('images/events/defaults'));

        $eventImage = collect($eventImages)->random();

        $title = fake()->word();
        return [
            'title'=> $title,
            'slug' => Str::slug($title),

            'short_description' => fake()->sentence(),
            'long_description' => fake()->sentence(),

            'start_date' => fake()->date(),
            'end_date' => fake()->date(),

            'start_time' => fake()->dateTime(),
            'end_time' => fake()->dateTime(),

            'location' => fake()->country(),
            'city' => fake()->city(),
            'street' => fake()->streetAddress(),
            'postal_code' => fake()->postcode(),
            'company_id' => Company::factory(),
            'image_path' => 'images/events/defaults/' . $eventImage->getFilename(),
            'category_id' => Category::inRandomOrder()->first()->id
        ];
    }
}
