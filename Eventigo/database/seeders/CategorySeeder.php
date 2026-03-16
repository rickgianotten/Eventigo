<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Music', 
            'slug' => Str::slug('music'),
            'icon' => 'music_note',
            'color' => 'orange'],

            ['name' => 'Food & Drinks', 
            'slug' => Str::slug('food & drinks'),
            'icon' => 'fastfood',
            'color' => 'green'],

            ['name' => 'Tech',
             'slug' => Str::slug('tech'),
            'icon' => 'computer',
            'color' => 'blue'],

            ['name' => 'Art', 
            'slug' => Str::slug('art'),
            'icon' => 'wall_art',
            'color' => 'yellow'],

            ['name' => 'Sport', 
            'slug' => Str::slug('sport'),
            'icon' => 'sports_baseball',
            'color' => 'red'],

            ['name' => 'Comedy', 
            'slug' => Str::slug('comedy'),
            'icon' => 'comedy_mask',
            'color' => 'purple'],          
        ];
        foreach($categories as $category){
            Category::updateOrCreate([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'icon'=> $category['icon'],
                'color' => $category['color']]);
        };
    }
}
