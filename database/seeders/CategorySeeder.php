<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Beverages & Soft Drinks',
                'description' => 'Cartons of carbonated soft drinks, isotonic beverages, mineral water, and fruit juices.',
                'icon' => 'drink',
                'sort_order' => 1,
            ],
            [
                'name' => 'Milk & Dairy Drinks',
                'description' => 'UHT milk cartons, condensed milk, evaporated creamer, and malt chocolate drinks in bulk.',
                'icon' => 'milk',
                'sort_order' => 2,
            ],
            [
                'name' => 'Cooking Oil & Essentials',
                'description' => 'Commercial 5kg/17kg cooking oil tins and cartons, pure ghee, and blended oils.',
                'icon' => 'oil',
                'sort_order' => 3,
            ],
            [
                'name' => 'Rice, Noodles & Grains',
                'description' => 'Bulk cartons of instant noodles, vermicelli, pasta, fragrant rice, and basmati.',
                'icon' => 'noodle',
                'sort_order' => 4,
            ],
            [
                'name' => 'Canned Food & Condiments',
                'description' => 'Cases of canned sardines, tuna, baked beans, tomato paste, soya sauce, and chilli sauces.',
                'icon' => 'can',
                'sort_order' => 5,
            ],
            [
                'name' => 'Biscuits, Snacks & Crackers',
                'description' => 'Wholesale cartons of sandwich biscuits, potato chips, wafers, and snack packets.',
                'icon' => 'snack',
                'sort_order' => 6,
            ],
            [
                'name' => 'Coffee, Tea & Cocoa',
                'description' => 'Bulk 3-in-1 coffee cartons, premium tea bags, and hot chocolate mixes.',
                'icon' => 'coffee',
                'sort_order' => 7,
            ],
            [
                'name' => 'Laundry & Fabric Care',
                'description' => 'Liquid and powder detergent cartons, fabric softeners, and stain removers.',
                'icon' => 'laundry',
                'sort_order' => 8,
            ],
            [
                'name' => 'Household Paper & Tissues',
                'description' => 'Cartons of facial tissues, kitchen towel rolls, and bulk toilet tissue rolls.',
                'icon' => 'paper',
                'sort_order' => 9,
            ],
            [
                'name' => 'Sanitation & Disinfectants',
                'description' => 'Commercial floor cleaners, bleach cases, dishwashing paste, and hand wash gallons.',
                'icon' => 'cleaning',
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $cat) {
            $slug = Str::slug($cat['name']);
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                    'meta_title' => $cat['name'] . ' Wholesale Cartons | 99 Bulk Sales',
                    'meta_description' => $cat['description'] . ' Best wholesale prices in cartons with prompt delivery.',
                    'robots' => 'index, follow',
                    'schema_type' => 'CollectionPage',
                ]
            );
        }
    }
}

