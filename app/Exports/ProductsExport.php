<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with(['category', 'brand'])->get();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Name',
            'Category',
            'Brand',
            'Pack Size',
            'Unit Description',
            'Weight/Volume',
            'Price (RM)',
            'Purchase Price (RM)',
            'Sale Price (RM)',
            'Stock Quantity',
            'Is Active (1/0)',
            'Is Featured (1/0)',
            'Is Promo (1/0)',
            'Is New (1/0)',
        ];
    }

    public function map($product): array
    {
        return [
            $product->sku,
            $product->name,
            $product->category?->name,
            $product->brand?->name,
            $product->pack_size,
            $product->unit_description,
            $product->weight_volume,
            $product->price,
            $product->purchase_price,
            $product->sale_price,
            $product->stock_quantity,
            $product->is_active ? 1 : 0,
            $product->is_featured ? 1 : 0,
            $product->is_promo ? 1 : 0,
            $product->is_new ? 1 : 0,
        ];
    }
}

