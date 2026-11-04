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
                'title' => 'Everyday Low Mart Pricing',
                'description' => 'Affordable everyday groceries and household savings direct from trusted brands without excessive markups.',
                'icon' => 'tag',
                'sort_order' => 1,
            ],
            [
                'title' => 'Free Local Delivery Radius',
                'description' => 'Enjoy free delivery within our local radius and nominal rates for adjoining zones calculated automatically.',
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
                'title' => 'Secure DuitNow QR & Online Pay',
                'description' => 'Pay with instant DuitNow QR or bank transfer to confirm your order. Simple, fast and secure.',
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

