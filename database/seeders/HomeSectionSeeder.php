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
                'title' => 'Shop Mart by Category',
                'subtitle' => 'Select a category to browse full carton listings and Mart prices',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'promo',
                'title' => 'Hot Promotions & Carton Deals',
                'subtitle' => 'Special discounted prices on high-demand Mart supplies',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'new_arrivals',
                'title' => 'New Bulk Arrivals',
                'subtitle' => 'Latest fresh stock and commercial items added to our warehouse',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'featured',
                'title' => 'Featured Mart Staples',
                'subtitle' => 'Everyday essential carton cartons trusted by local businesses and homes',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'how_it_works',
                'title' => 'How Ordering Works',
                'subtitle' => 'Straightforward, safe bulk delivery right to your door in 4 simple steps',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'benefits',
                'title' => 'Why Choose 99 Bulk Sales?',
                'subtitle' => 'Built for Mart volume, reliable service, and transparent pricing',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'key' => 'faqs',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Everything you need to know about delivery, floor charges, and payment proof',
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

