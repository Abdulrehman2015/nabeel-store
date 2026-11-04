<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Fresh Groceries & Everyday Essentials',
                'subtitle' => 'Shop beverages, pantry staples, snacks, dairy, and household cleaning essentials delivered right to your door.',
                'badge' => '⚡ FRESH MART SAVINGS',
                'link' => '/category/beverages-soft-drinks',
                'button_text' => 'Shop Groceries',
                'desktop_image' => 'images/banner-1.jpg',
                'mobile_image' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Pantry Staples, Cooking Oil & Rice',
                'subtitle' => 'Quality cooking oils, premium fragrant rice, sauces, and family food essentials with prompt local delivery.',
                'badge' => '🔥 DAILY HOME ESSENTIALS',
                'link' => '/category/cooking-oil-essentials',
                'button_text' => 'Explore Pantry',
                'desktop_image' => 'images/banner-2.jpg',
                'mobile_image' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Free Local Delivery Within 2 Kilometers',
                'subtitle' => 'Fast doorstep delivery up to Floor 1 at zero extra charge for your neighbourhood.',
                'badge' => '🚚 FAST LOCAL DELIVERY',
                'link' => '/categories',
                'button_text' => 'Shop Now',
                'desktop_image' => 'images/banner-3.jpg',
                'mobile_image' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $b) {
            Banner::updateOrCreate(
                ['title' => $b['title']],
                $b
            );
        }
    }
}

