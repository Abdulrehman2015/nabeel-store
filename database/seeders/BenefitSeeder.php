<?php

namespace Database\Seeders;

use App\Models\Benefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    public function run(): void
    {
        $benefits = [
            [
                'title' => 'Factory Carton Mart Pricing',
                'description' => 'Unbeatable bulk carton savings direct from primary manufacturers and distributors without retail markups.',
                'icon' => 'tag',
                'sort_order' => 1,
            ],
            [
                'title' => 'Free Local Delivery Radius',
                'description' => 'Enjoy free delivery within our 5km radius and nominal rates for adjoining zones calculated automatically.',
                'icon' => 'truck',
                'sort_order' => 2,
            ],
            [
                'title' => 'Transparent Floor Charges',
                'description' => 'Clear, upfront floor handling rates. Free delivery up to 1st floor with minimal fee for upper levels.',
                'icon' => 'building',
                'sort_order' => 3,
            ],
            [
                'title' => 'Secure DuitNow QR Advance',
                'description' => 'Pay only 10% advance deposit via instant QR to secure carton stock. Pay the remaining balance upon delivery.',
                'icon' => 'shield-check',
                'sort_order' => 4,
            ],
        ];

        foreach ($benefits as $b) {
            Benefit::updateOrCreate(
                ['title' => $b['title']],
                $b
            );
        }
    }
}

