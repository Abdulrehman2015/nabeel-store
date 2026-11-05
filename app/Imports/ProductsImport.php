<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    protected array $categories;
    protected array $brands;

    public function __construct()
    {
        $this->categories = Category::pluck('id', 'name')->toArray();
        $this->brands = Brand::pluck('id', 'name')->toArray();
    }

    public function model(array $row)
    {
        $sku = trim($row['sku'] ?? '');
        $name = trim($row['name'] ?? '');

        if (empty($sku) || empty($name)) {
            return null;
        }

        $catName = trim($row['category'] ?? '');
        $categoryId = $this->categories[$catName] ?? null;

        if (!$categoryId && !empty($catName)) {
            $cat = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'is_active' => true,
            ]);
            $categoryId = $cat->id;
            $this->categories[$catName] = $categoryId;
        }

        if (!$categoryId) {
            $categoryId = Category::first()->id;
        }

        $brandName = trim($row['brand'] ?? '');
        $brandId = null;
        if (!empty($brandName)) {
            $brandId = $this->brands[$brandName] ?? null;
            if (!$brandId) {
                $b = Brand::create(['name' => $brandName, 'slug' => Str::slug($brandName), 'is_active' => true]);
                $brandId = $b->id;
                $this->brands[$brandName] = $brandId;
            }
        }

        return Product::updateOrCreate(
            ['sku' => $sku],
            [
                'name' => $name,
                'slug' => Str::slug($name),
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'pack_size' => $row['pack_size'] ?? 'Carton',
                'unit_description' => $row['unit_description'] ?? null,
                'weight_volume' => $row['weight_volume'] ?? null,
                'price' => (float) ($row['price_rm'] ?? $row['price'] ?? 0),
                'purchase_price' => (float) ($row['purchase_price_rm'] ?? $row['purchase_price'] ?? $row['cost_price'] ?? 0),
                'sale_price' => !empty($row['sale_price_rm'] ?? $row['sale_price'] ?? null) ? (float) ($row['sale_price_rm'] ?? $row['sale_price']) : null,
                'stock_quantity' => (int) ($row['stock_quantity'] ?? 0),
                'is_active' => (bool) ($row['is_active_10'] ?? $row['is_active'] ?? 1),
                'is_featured' => (bool) ($row['is_featured_10'] ?? $row['is_featured'] ?? 0),
                'is_promo' => (bool) ($row['is_promo_10'] ?? $row['is_promo'] ?? 0),
                'is_new' => (bool) ($row['is_new_10'] ?? $row['is_new'] ?? 0),
            ]
        );
    }
}

