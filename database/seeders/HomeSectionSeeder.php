<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'categories',
                'title' => 'Shop by Category',
                'subtitle' => 'Browse our fresh groceries, beverages, snacks, and daily household essentials.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'promo',
                'title' => 'Featured Store Specials & Hot Deals',
                'subtitle' => 'Limited-time discounts on fresh groceries and everyday family favorites.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'new_arrivals',
                'title' => 'New Mart Arrivals',
                'subtitle' => 'Latest fresh stock and products added to our shelves.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'featured',
                'title' => 'Featured Mart Favorites',
                'subtitle' => 'Everyday essentials trusted by our neighbourhood families and shoppers.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'how_it_works',
                'title' => 'How Online Ordering Works',
                'subtitle' => 'Fast, easy ordering delivered right to your doorstep in 4 simple steps.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'benefits',
                'title' => 'Why Shop at N&J Mini Mart?',
                'subtitle' => 'Genuine quality, reliable neighbourhood delivery, and everyday honest pricing.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'key' => 'faqs',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Everything you need to know about delivery, floor rates, and easy payment.',
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $s) {
            HomeSection::updateOrCreate(
                ['key' => $s['key']],
                $s
            );
        }
    }
}

