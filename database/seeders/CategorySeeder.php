<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = config('wiki.default_categories');

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
