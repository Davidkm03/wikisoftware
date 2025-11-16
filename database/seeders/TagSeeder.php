<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = config('wiki.suggested_tags');
        $colors = ['blue', 'green', 'yellow', 'red', 'purple', 'pink', 'indigo', 'teal'];

        foreach ($tags as $index => $tagName) {
            Tag::create([
                'name' => $tagName,
                'color' => $colors[$index % count($colors)],
                'usage_count' => 0,
            ]);
        }
    }
}
