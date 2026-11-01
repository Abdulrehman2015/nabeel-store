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
                'title' => 'Direct Wholesale Groceries in Bulk Cartons',
                'subtitle' => 'Save up to 35% on genuine carton drinks, dairy, cooking oil, rice & cleaning supplies.',
                'badge' => '⚡ DIRECT BULK DISCOUNTS',
                'link' => '/category/beverages-soft-drinks',
                'button_text' => 'Shop Bulk Drinks',
                'desktop_image' => 'images/banner-1.jpg',
                'mobile_image' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Commercial Cooking Oil & Food Essentials',
                'subtitle' => '5kg 4-pack cartons and 17kg commercial catering tins with prompt delivery.',
                'badge' => '🔥 RESTAURANTS & BUSINESSES',
                'link' => '/category/cooking-oil-essentials',
                'button_text' => 'View Cooking Oil',
                'desktop_image' => 'images/banner-2.jpg',
                'mobile_image' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Free Delivery Within 5 Kilometers',
                'subtitle' => 'Direct doorstep delivery up to 1st floor at zero extra charge for local deliveries.',
                'badge' => '🚚 LOCAL BULK DELIVERY',
                'link' => '/checkout',
                'button_text' => 'Check Delivery Zone',
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
