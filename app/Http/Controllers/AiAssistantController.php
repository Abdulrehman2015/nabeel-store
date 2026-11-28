<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiAssistantController extends Controller
{
    /**
     * Store-only AI Assistant endpoint.
     * Strictly answers questions about this store, products, categories, orders, delivery, and policies.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:400',
        ]);

        $rawMessage = trim($validated['message']);
        $lower = Str::lower($rawMessage);
        $storeName = store_name();

        // 1. Check for obvious off-topic keywords before anything else
        if ($this->isClearlyOffTopic($lower)) {
            return response()->json([
                'reply' => "I am the {$storeName} Assistant and can only help with our store, products, prices, categories, delivery, orders, and store policies. Please ask something related to {$storeName}!",
                'links' => [],
            ]);
        }

        // 2. Gather matching live store context (products, categories, FAQs)
        $context = $this->gatherStoreContext($lower);

        // 3. If GEMINI_API_KEY is configured, use Gemini with strict store-only guardrail
        $apiKey = env('GEMINI_API_KEY');
        if (!empty($apiKey)) {
            $aiReply = $this->askGemini($apiKey, $rawMessage, $storeName, $context);
            if ($aiReply !== null) {
                return response()->json([
                    'reply' => $aiReply,
                    'links' => $context['links'],
                ]);
            }
        }

        // 4. Built-in zero-dependency Store Assistant (instant, always works)
        return response()->json($this->buildLocalStoreReply($lower, $storeName, $context));
    }

    /**
     * Detect clearly unrelated / off-topic requests.
     */
    protected function isClearlyOffTopic(string $lower): bool
    {
        $blockedPatterns = [
            '/\b(python|javascript|java|c\+\+|html|css|php|laravel|react|sql|code|program|script|algorithm|debug)\b/i',
            '/\b(president|prime minister|election|politics|government|war|history|geography|capital of)\b/i',
            '/\b(weather|forecast|temperature|cricket|football|soccer|movie|song|lyrics|anime|joke|poem|story|essay)\b/i',
            '/\b(solve|equation|integral|derivative|homework|assignment|translate|grammar)\b/i',
            '/\b(bitcoin|crypto|stock market|forex|horoscope|astrology)\b/i',
        ];

        foreach ($blockedPatterns as $pattern) {
            if (preg_match($pattern, $lower)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Search live store products, categories, and FAQs matching the user's query.
     */
    protected function gatherStoreContext(string $lower): array
    {
        $stopWords = ['i', 'me', 'my', 'we', 'you', 'your', 'the', 'a', 'an', 'is', 'are', 'was', 'do', 'does', 'have', 'has', 'can', 'could', 'please', 'tell', 'about', 'what', 'which', 'where', 'when', 'how', 'much', 'price', 'cost', 'buy', 'want', 'need', 'looking', 'for', 'any', 'show', 'give', 'in', 'on', 'at', 'to', 'of', 'and', 'or', 'store', 'shop', 'product', 'products', 'item', 'items', 'available', 'stock', 'hai', 'hain', 'ka', 'ki', 'ke', 'kya', 'kitne', 'batao', 'chahiye', 'chahie', 'mil', 'sakta', 'milega'];

        $words = preg_split('/[\s,\?\.\!]+/', $lower, -1, PREG_SPLIT_NO_EMPTY);
        $keywords = array_values(array_filter($words, fn ($w) => strlen($w) >= 2 && !in_array($w, $stopWords, true)));

        $products = collect();
        if (!empty($keywords)) {
            $products = Product::active()
                ->with('category:id,name,slug')
                ->where(function ($q) use ($keywords) {
                    foreach (array_slice($keywords, 0, 5) as $kw) {
                        $q->orWhere('name', 'like', "%{$kw}%")
                          ->orWhere('sku', 'like', "%{$kw}%")
                          ->orWhere('pack_size', 'like', "%{$kw}%")
                          ->orWhereHas('category', fn ($cq) => $cq->where('name', 'like', "%{$kw}%"));
                    }
                })
                ->take(5)
                ->get();
        }

        if ($products->isEmpty() && Str::contains($lower, ['popular', 'featured', 'best', 'deal', 'promo', 'offer', 'discount', 'product', 'catalog'])) {
            $products = Product::active()
                ->with('category:id,name,slug')
                ->orderByDesc('is_promo')
                ->orderByDesc('is_featured')
                ->take(5)
                ->get();
        }

        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $faqs = collect();
        if (!empty($keywords)) {
            $faqs = Faq::active()
                ->where(function ($q) use ($keywords) {
                    foreach (array_slice($keywords, 0, 4) as $kw) {
                        $q->orWhere('question', 'like', "%{$kw}%")
                          ->orWhere('answer', 'like', "%{$kw}%");
                    }
                })
                ->take(2)
                ->get();
        }

        $links = $products->map(fn ($p) => [
            'label' => $p->name . ' — ' . format_currency($p->current_price) . ($p->in_stock ? ' (In Stock)' : ' (Out of Stock)'),
            'url' => route('product.show', $p->slug),
        ])->values()->all();

        return [
            'keywords' => $keywords,
            'products' => $products,
            'categories' => $categories,
            'faqs' => $faqs,
            'links' => $links,
        ];
    }

    /**
     * Call Gemini API with strict store-only system instructions.
     */
    protected function askGemini(string $apiKey, string $message, string $storeName, array $context): ?string
    {
        try {
            $catList = $context['categories']->pluck('name')->implode(', ');
            $prodLines = $context['products']->map(fn ($p) =>
                "- {$p->name} | Price: " . format_currency($p->current_price) . " | Stock: {$p->stock_quantity} | Pack: {$p->pack_size}"
            )->implode("\n");
            $faqLines = $context['faqs']->map(fn ($f) => "Q: {$f->question}\nA: {$f->answer}")->implode("\n");

            $systemPrompt = "You are the official AI Assistant for '{$storeName}', a retail/grocery & bulk mart store.\n"
                . "STRICT RULE: You MUST ONLY answer questions related to {$storeName}, its products, prices, categories, stock, delivery, floor charges, order tracking, payment methods, contact details, and store policies. "
                . "If the user asks ANYTHING unrelated to this store (general knowledge, coding, politics, jokes, math, weather, other stores, etc.), politely refuse and say you can only assist with {$storeName} store inquiries.\n"
                . "Keep answers concise, helpful, and friendly (under 4 sentences).\n\n"
                . "STORE INFO:\n"
                . "- Store Name: {$storeName}\n"
                . "- Phone/Hotline: " . setting('store_phone', '+60 12-889 9900') . "\n"
                . "- Address: " . setting('store_address', 'Malaysia') . "\n"
                . "- Working Hours: " . setting('working_hours', 'Mon-Sat 8am-7pm') . "\n"
                . "- Free Delivery Radius: " . setting('free_delivery_radius', 5) . " km (Free up to 1st Floor)\n"
                . "- Minimum Order: " . format_currency(setting('minimum_order_amount', 0)) . "\n"
                . "- Payment Methods: DuitNow QR, Bank/Instant Transfer, Balance on Delivery\n"
                . "- Categories: {$catList}\n"
                . ($prodLines ? "\nMATCHING PRODUCTS:\n{$prodLines}\n" : '')
                . ($faqLines ? "\nRELEVANT FAQS:\n{$faqLines}\n" : '');

            $response = Http::timeout(6)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $message]]],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.3,
                        'maxOutputTokens' => 250,
                    ],
                ]
            );

            if ($response->successful()) {
                $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
                if (is_string($text) && trim($text) !== '') {
                    return trim($text);
                }
            }
        } catch (\Throwable $e) {
            // Fall back silently to local store engine
        }

        return null;
    }

    /**
     * Built-in deterministic Store Assistant using live MySQL data.
     */
    protected function buildLocalStoreReply(string $lower, string $storeName, array $context): array
    {
        // Greetings
        if (preg_match('/^(hi|hello|hey|salam|assalam|assalamualaikum|aoa|good morning|good afternoon|good evening)\b/i', $lower)) {
            return [
                'reply' => "Welcome to {$storeName}! 👋 I can help you find products, check prices & stock, explain delivery rates, or track your order. What would you like to know about our store?",
                'links' => [
                    ['label' => '🛍️ Browse All Products', 'url' => route('products.index')],
                    ['label' => '🗂️ View Categories', 'url' => route('category.index')],
                ],
            ];
        }

        // Matching products found
        if ($context['products']->isNotEmpty()) {
            $count = $context['products']->count();
            return [
                'reply' => "Here are {$count} matching product(s) from {$storeName}. Click any item below to view full details or add it to your cart:",
                'links' => $context['links'],
            ];
        }

        // Delivery / Shipping / Floor charges
        if (Str::contains($lower, ['deliver', 'shipping', 'radius', 'floor', 'charge', 'fee', 'km', 'free delivery'])) {
            $radius = setting('free_delivery_radius', 5);
            return [
                'reply' => "{$storeName} offers FREE delivery within a {$radius} km radius and free delivery up to the 1st floor! You can check exact zone rates at checkout.",
                'links' => [
                    ['label' => '🚚 Shipping & Floor Rates', 'url' => route('page.show', 'shipping-and-delivery-policy')],
                    ['label' => '🛒 Go to Cart', 'url' => route('cart.index')],
                ],
            ];
        }

        // Order Tracking
        if (Str::contains($lower, ['track', 'order status', 'my order', 'where is my order', 'order'])) {
            return [
                'reply' => "You can track your {$storeName} order status anytime using your Order Number on our Track Order page.",
                'links' => [
                    ['label' => '📦 Track Your Order', 'url' => route('order.track')],
                ],
            ];
        }

        // Contact / Hours / Location / WhatsApp
        if (Str::contains($lower, ['contact', 'phone', 'hotline', 'call', 'whatsapp', 'address', 'location', 'hour', 'timing', 'open', 'close'])) {
            $phone = setting('store_phone', '+60 12-889 9900');
            $hours = setting('working_hours', 'Mon-Sat 8am-7pm');
            $addr = setting('store_address', 'Malaysia');
            return [
                'reply' => "You can reach {$storeName} at {$phone} during {$hours}. Store location: {$addr}.",
                'links' => [
                    ['label' => '📞 Contact Us Page', 'url' => route('page.contact')],
                ],
            ];
        }

        // Payment / Refund / Return / Policy
        if (Str::contains($lower, ['pay', 'payment', 'qr', 'duitnow', 'bank', 'cash', 'cod', 'refund', 'return', 'cancel', 'policy'])) {
            return [
                'reply' => "{$storeName} accepts DuitNow QR, Maybank / Instant Transfer, and Balance on Delivery. Check our Payment & Return policies below for details.",
                'links' => [
                    ['label' => '💳 Payment Policy', 'url' => route('page.show', 'payment-policy')],
                    ['label' => '↩️ Refund & Return Policy', 'url' => route('page.show', 'refund-and-return-policy')],
                ],
            ];
        }

        // Categories
        if (Str::contains($lower, ['category', 'categories', 'section', 'aisle', 'department'])) {
            $names = $context['categories']->take(8)->pluck('name')->implode(', ');
            return [
                'reply' => "We have {$context['categories']->count()} categories at {$storeName}, including: {$names}.",
                'links' => [
                    ['label' => '🗂️ View All Categories', 'url' => route('category.index')],
                ],
            ];
        }

        // FAQ match
        if ($context['faqs']->isNotEmpty()) {
            $faq = $context['faqs']->first();
            return [
                'reply' => $faq->answer,
                'links' => [
                    ['label' => '❓ View All FAQs', 'url' => route('page.faqs')],
                ],
            ];
        }

        // Strict store-only fallback refusal for unrelated/unmatched queries
        return [
            'reply' => "I am the {$storeName} Store Assistant and I only answer questions related to our products, prices, categories, delivery, orders, and store policies. Try searching a product name or tapping one of the quick options below!",
            'links' => [
                ['label' => '🛍️ All Products', 'url' => route('products.index')],
                ['label' => '❓ FAQs', 'url' => route('page.faqs')],
                ['label' => '📞 Contact Support', 'url' => route('page.contact')],
            ],
        ];
    }
}
