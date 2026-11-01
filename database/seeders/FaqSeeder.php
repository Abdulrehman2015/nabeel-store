<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'category' => 'Delivery',
                'question' => 'How does the free delivery radius work?',
                'answer' => 'We offer completely Free Delivery for addresses located within 5 km straight-line distance of our warehouse. For addresses between 5 km and 7 km, a nominal delivery charge is applied. Beyond 7 km, checkout is restricted.',
                'sort_order' => 1,
            ],
            [
                'category' => 'Delivery',
                'question' => 'How are floor charges calculated?',
                'answer' => 'Delivery to Ground floor (Floor 0) and 1st floor (Floor 1) is completely FREE. For higher floors without a service lift or with stair delivery, a standard extra charge of RM 10.00 is charged per additional floor above Floor 1 (e.g. Floor 3 = 2 extra floors x RM 10 = RM 20).',
                'sort_order' => 2,
            ],
            [
                'category' => 'Payment',
                'question' => 'Why is an advance payment required?',
                'answer' => 'Because bulk cartons are heavy, high-value goods reserved specifically for your order, we require a small 10% advance deposit via DuitNow QR. This secures your inventory allocation and delivery slot. The remaining 90% balance is paid on delivery.',
                'sort_order' => 3,
            ],
            [
                'category' => 'Payment',
                'question' => 'How do I submit payment proof?',
                'answer' => 'During checkout, you will see our DuitNow QR code and the exact advance deposit amount. Scan and complete the transfer in your banking app, take a screenshot, and input the Transaction/Reference ID and upload the image before placing the order.',
                'sort_order' => 4,
            ],
            [
                'category' => 'Ordering',
                'question' => 'Can I order as a guest without creating an account?',
                'answer' => 'Yes! You can check out as a guest. All you need is your name, phone number, and delivery address. You can track your order at any time using your Order Number and phone number.',
                'sort_order' => 5,
            ],
            [
                'category' => 'Ordering',
                'question' => 'What is the minimum order value?',
                'answer' => 'The minimum order value for bulk sales is RM 100.00 per order to ensure carton logistics viability.',
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::updateOrCreate(
                ['question' => $f['question']],
                $f
            );
        }
    }
}
