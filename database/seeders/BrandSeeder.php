<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Coca-Cola', 'Fanta', '100 Plus', 'Milo', 'Dutch Lady',
            'Buruh', 'Seri Murni', 'Maggi', 'Ayam Brand', 'Julie\'s',
            'Nescafe', 'Breeze', 'Dynamo', 'Premier', 'Dettol'
        ];

        foreach ($brands as $name) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            );
        }
    }
}

