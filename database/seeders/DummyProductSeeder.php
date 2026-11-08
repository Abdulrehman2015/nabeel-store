<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;

class DummyProductSeeder extends Seeder
{
    public function run(): void
    {
        $catBeverages = Category::firstOrCreate(
            ['slug' => 'beverages-drinks'],
            [
                'name' => 'Beverages & Drinks',
                'description' => 'Refreshing juices, tea, coffee, sodas and isotonic energy drinks',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $catGroceries = Category::firstOrCreate(
            ['slug' => 'groceries-instant-food'],
            [
                'name' => 'Groceries & Instant Food',
                'description' => 'Pantry staples, instant noodles, cooking ingredients and canned goods',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $catSnacks = Category::firstOrCreate(
            ['slug' => 'snacks-biscuits'],
            [
                'name' => 'Snacks & Biscuits',
                'description' => 'Crispy chips, cookies, wafers and sweet snacks for every mood',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        $brandNestle = Brand::firstOrCreate(
            ['slug' => 'nestle'],
            ['name' => 'Nestlé', 'is_active' => true]
        );

        $brandMaggi = Brand::firstOrCreate(
            ['slug' => 'maggi'],
            ['name' => 'Maggi', 'is_active' => true]
        );

        $brand100Plus = Brand::firstOrCreate(
            ['slug' => '100-plus'],
            ['name' => '100 Plus', 'is_active' => true]
        );

        $brandOreo = Brand::firstOrCreate(
            ['slug' => 'oreo'],
            ['name' => 'Oreo', 'is_active' => true]
        );

        $products = [
            [
                'category_id' => $catBeverages->id,
                'brand_id' => $brandNestle->id,
                'name' => 'Milo Activ-Go Chocolate Malt Powder 1kg',
                'slug' => 'milo-activ-go-chocolate-malt-powder-1kg',
                'sku' => 'DRK-MILO-1KG',
                'pack_size' => '1kg',
                'unit_description' => 'Refill Pouch',
                'weight_volume' => '1000g',
                'price' => 21.50,
                'purchase_price' => 16.50,
                'sale_price' => null,
                'stock_quantity' => 50,
                'low_stock_threshold' => 10,
                'short_description' => 'Popular nutritious chocolate malt drink packed with vitamins and minerals.',
                'description' => 'Nestlé Milo Activ-Go provides energy to go further with Protomalt, vitamins, and minerals. Perfect hot or cold.',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => false,
                'is_promo' => false,
            ],
            [
                'category_id' => $catGroceries->id,
                'brand_id' => $brandMaggi->id,
                'name' => 'Maggi 2-Minute Curry Instant Noodles (5 x 79g)',
                'slug' => 'maggi-2-minute-curry-instant-noodles-5-x-79g',
                'sku' => 'GRO-MAGGI-CUR',
                'pack_size' => '5 x 79g',
                'unit_description' => 'Family Multipack',
                'weight_volume' => '395g',
                'price' => 6.80,
                'purchase_price' => 4.80,
                'sale_price' => null,
                'stock_quantity' => 80,
                'low_stock_threshold' => 15,
                'short_description' => 'Signature Malaysian savory curry instant noodles made with real spices.',
                'description' => 'Rich savory broth crafted from 12 secret aromatic spices paired with springy wheat noodles.',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => false,
                'is_promo' => false,
            ],
            [
                'category_id' => $catBeverages->id,
                'brand_id' => $brand100Plus->id,
                'name' => '100 Plus Isotonic Drink Can 325ml',
                'slug' => '100-plus-isotonic-drink-can-325ml',
                'sku' => 'DRK-100P-CAN',
                'pack_size' => '325ml',
                'unit_description' => 'Aluminum Can',
                'weight_volume' => '325ml',
                'price' => 2.40,
                'purchase_price' => 1.60,
                'sale_price' => null,
                'stock_quantity' => 120,
                'low_stock_threshold' => 20,
                'short_description' => 'Carbonated hydration beverage restoring lost electrolytes quickly.',
                'description' => 'Specially formulated to rehydrate and replenish nutrients and minerals lost during active routines.',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => true,
                'is_promo' => false,
            ],
            [
                'category_id' => $catSnacks->id,
                'brand_id' => $brandOreo->id,
                'name' => 'Oreo Original Vanilla Creme Biscuits 120g',
                'slug' => 'oreo-original-vanilla-creme-biscuits-120g',
                'sku' => 'SNK-OREO-120',
                'pack_size' => '120g',
                'unit_description' => 'Single Roll Pack',
                'weight_volume' => '120g',
                'price' => 3.90,
                'purchase_price' => 2.60,
                'sale_price' => null,
                'stock_quantity' => 70,
                'low_stock_threshold' => 12,
                'short_description' => 'Classic chocolate sandwich cookie with sweet vanilla cream filling.',
                'description' => 'Twist, lick, and dunk! The world famous rich cocoa sandwich biscuit filled with smooth vanilla cream.',
                'is_active' => true,
                'is_featured' => false,
                'is_new' => false,
                'is_promo' => false,
            ],
            [
                'category_id' => $catBeverages->id,
                'brand_id' => $brandNestle->id,
                'name' => 'Nescafe Classic Refill Instant Coffee 200g',
                'slug' => 'nescafe-classic-refill-instant-coffee-200g',
                'sku' => 'DRK-NESC-200',
                'pack_size' => '200g',
                'unit_description' => 'Economy Refill Pouch',
                'weight_volume' => '200g',
                'price' => 14.50,
                'purchase_price' => 10.80,
                'sale_price' => null,
                'stock_quantity' => 40,
                'low_stock_threshold' => 8,
                'short_description' => '100% pure roasted robusta coffee beans delivering signature rich aroma.',
                'description' => 'Awaken your senses with rich and intense dark roast flavors crafted from responsibly sourced coffee beans.',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => false,
                'is_promo' => true,
            ],
        ];

        foreach ($products as $prodData) {
            Product::updateOrCreate(
                ['sku' => $prodData['sku']],
                $prodData
            );
        }
    }
}

