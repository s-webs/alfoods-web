<?php

namespace Database\Seeders;

use App\Models\Category;
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
            [
                'name' => 'Овощи и фрукты',
                'color_from' => '#22c55e',
                'color_to' => '#16a34a',
                'sort_order' => 1,
            ],
            [
                'name' => 'Молочные продукты',
                'color_from' => '#3b82f6',
                'color_to' => '#2563eb',
                'sort_order' => 2,
            ],
            [
                'name' => 'Сладости и выпечка',
                'color_from' => '#f59e0b',
                'color_to' => '#d97706',
                'sort_order' => 3,
            ],
            [
                'name' => 'Хлеб и бакалея',
                'color_from' => '#a16207',
                'color_to' => '#854d0e',
                'sort_order' => 4,
            ],
            [
                'name' => 'Напитки',
                'color_from' => '#06b6d4',
                'color_to' => '#0891b2',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $data) {
            Category::create([
                'parent_id' => null,
                'image' => null,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'sort_order' => $data['sort_order'],
                'color_from' => $data['color_from'],
                'color_to' => $data['color_to'],
                'is_active' => true,
            ]);
        }
    }
}
