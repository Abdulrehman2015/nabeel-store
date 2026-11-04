<?php

namespace Database\Seeders;

use App\Models\HowItWorksStep;
use Illuminate\Database\Seeder;

class HowItWorksSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'step_number' => 1,
                'title' => 'Browse Cartons & Add to Cart',
                'description' => 'Explore Groceries & Marts of drinks, dairy, staples, and supplies. Add items to your cart with no complicated registration.',
                'icon' => 'cart',
                'sort_order' => 1,
            ],
            [
                'step_number' => 2,
                'title' => 'Pin Delivery Location & Floor',
                'description' => 'Select your exact delivery address on the interactive map. Enter your floor number to see live free or paid delivery rates.',
                'icon' => 'map-pin',
                'sort_order' => 2,
            ],
            [
                'step_number' => 3,
                'title' => 'Scan QR & Upload Advance Proof',
                'description' => 'Scan the DuitNow QR to pay the small advance deposit (default 10%), then upload your payment screenshot and reference ID.',
                'icon' => 'qrcode',
                'sort_order' => 3,
            ],
            [
                'step_number' => 4,
                'title' => 'Quick Verification & Delivery',
                'description' => 'Our dispatch team verifies your proof immediately, prepares your carton goods, and delivers right to your floor with balance on delivery.',
                'icon' => 'truck',
                'sort_order' => 4,
            ],
        ];

        foreach ($steps as $st) {
            HowItWorksStep::updateOrCreate(
                ['step_number' => $st['step_number']],
                $st
            );
        }
    }
}

