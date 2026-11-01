<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Page;
use App\Services\CartService;
use App\Services\DeliveryService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected DeliveryService $deliveryService,
        protected OrderService $orderService,
        protected NotificationService $notificationService,
        protected SeoService $seoService
    ) {}

    public function index()
    {
        $cartSummary = $this->cartService->getSummary();

        if (empty($cartSummary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add wholesale cartons before checkout.');
        }

        if (!$cartSummary['is_minimum_met']) {
            return redirect()->route('cart.index')->with('error', 'Minimum order value of ' . $cartSummary['min_order_formatted'] . ' is required to checkout.');
        }

        // Store location for map defaults
        $storeLat = (float) setting('store_latitude', 3.0245);
        $storeLng = (float) setting('store_longitude', 101.6166);
        $freeRadius = (float) setting('free_delivery_radius', 5.0);
        $paidRadius = (float) setting('paid_delivery_radius', 2.0);
        $freeFloorLimit = (int) setting('free_floor_limit', 1);
        $extraFloorCharge = (float) setting('extra_floor_charge', 10.00);
        $advancePercentage = (float) setting('advance_percentage', 10.00);

        // Saved addresses if customer is authenticated
        $savedAddresses = auth()->check() ? auth()->user()->addresses : collect();

        // Legal policies required at checkout
        $checkoutPolicies = Page::where('is_published', true)
            ->where('show_at_checkout', true)
            ->get();

        $seo = $this->seoService->resolveMetadata(null, 'checkout', [
            'title' => 'Secure Checkout & Advance Payment',
            'robots' => 'noindex, nofollow',
        ]);

        $idempotencyToken = Str::uuid()->toString();

        return view('storefront.checkout', compact(
            'cartSummary',
            'storeLat',
            'storeLng',
            'freeRadius',
            'paidRadius',
            'freeFloorLimit',
            'extraFloorCharge',
            'advancePercentage',
            'savedAddresses',
            'checkoutPolicies',
            'seo',
            'idempotencyToken'
        ));
    }

    /**
     * AJAX endpoint called live on map pin drag or floor change.
     * Updates calculations dynamically without page reload.
     */
    public function evaluateZone(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'floor' => 'nullable|integer|min:0|max:100',
        ]);

        $lat = (float) $request->input('latitude');
        $lng = (float) $request->input('longitude');
        $floor = max(0, (int) $request->input('floor', 0));
        $subtotal = $this->cartService->getSubtotal();

        $eval = $this->deliveryService->evaluateDelivery($lat, $lng, $floor, $subtotal);

        $deliveryCharge = $eval['delivery_charge'];
        $floorCharge = $eval['floor_charge'];
        $grandTotal = round($subtotal + $deliveryCharge + $floorCharge, 2);

        $advancePct = (float) setting('advance_percentage', 10.00);
        $requireAdvance = (bool) setting('require_advance_payment', true);

        if ($requireAdvance && $advancePct > 0) {
            $advanceAmount = round(($grandTotal * $advancePct) / 100, 2);
        } else {
            $advanceAmount = 0.00;
        }

        $balanceAmount = round($grandTotal - $advanceAmount, 2);

        return response()->json([
            'eligible' => $eval['eligible'],
            'zone' => $eval['zone'],
            'distance_km' => $eval['distance_km'],
            'delivery_charge' => $deliveryCharge,
            'delivery_charge_formatted' => $deliveryCharge > 0 ? format_currency($deliveryCharge) : 'FREE',
            'floor_charge' => $floorCharge,
            'floor_charge_formatted' => $floorCharge > 0 ? format_currency($floorCharge) : 'FREE',
            'subtotal' => $subtotal,
            'subtotal_formatted' => format_currency($subtotal),
            'grand_total' => $grandTotal,
            'grand_total_formatted' => format_currency($grandTotal),
            'advance_percentage' => $advancePct,
            'advance_amount' => $advanceAmount,
            'advance_amount_formatted' => format_currency($advanceAmount),
            'balance_amount' => $balanceAmount,
            'balance_amount_formatted' => format_currency($balanceAmount),
            'message' => $eval['message'],
        ]);
    }

    /**
     * Submit order with payment proof.
     * Enforces rate limiting, CSRF, validation, and server-side recalculation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|min:8|max:25',
            'customer_email' => 'required|email|max:150',
            'delivery_address' => 'required|string|max:300',
            'delivery_city' => 'required|string|max:100',
            'floor' => 'required|integer|min:0|max:100',
            'landmark' => 'nullable|string|max:150',
            'order_notes' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'payment_reference' => 'required|string|min:4|max:100|unique:orders,payment_reference',
            'payment_proof' => 'required|file|mimes:jpeg,jpg,png,webp,pdf|max:5120', // max 5MB
            'idempotency_token' => 'nullable|string|max:64',
            'terms_accepted' => 'required|accepted',
        ], [
            'floor.required' => 'The Floor level is required. Please enter 0 for Ground floor.',
            'payment_reference.required' => 'Please provide the transaction or reference ID from your payment receipt.',
            'payment_reference.unique' => 'This Transaction Reference ID has already been recorded for another order.',
            'payment_proof.required' => 'Please upload a clear screenshot of your completed advance payment transfer.',
            'terms_accepted.accepted' => 'You must agree to our Terms & Conditions and Privacy Policy to proceed.',
        ]);

        try {
            $data = $request->only([
                'customer_name',
                'customer_phone',
                'customer_email',
                'delivery_address',
                'delivery_city',
                'floor',
                'landmark',
                'order_notes',
                'latitude',
                'longitude',
                'payment_reference',
                'idempotency_token',
            ]);

            // Save policy consent audit
            $data['accepted_policy_versions'] = [
                'timestamp' => now()->toIso8601String(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'terms_accepted' => true,
            ];

            // Save address if customer checked "Save this address"
            if ($request->boolean('save_address') && auth()->check()) {
                CustomerAddress::create([
                    'user_id' => auth()->id(),
                    'label' => $request->input('address_label', 'Delivery Address'),
                    'recipient_name' => $data['customer_name'],
                    'recipient_phone' => $data['customer_phone'],
                    'address_line' => $data['delivery_address'],
                    'area_city' => $data['delivery_city'],
                    'floor' => (int) $data['floor'],
                    'landmark' => $data['landmark'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                ]);
            }

            // Create order with locked stock and verified calculations
            $order = $this->orderService->createOrderWithProof($data, $request->file('payment_proof'));

            // Dispatch free background notifications (Mail, Telegram, CallMeBot)
            $this->notificationService->sendNewOrderNotifications($order);

            return redirect()->route('checkout.success', $order->order_number);
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Order confirmation / success page.
     */
    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();

        $whatsAppUrl = $this->notificationService->getWhatsAppClickUrl($order);

        $seo = $this->seoService->resolveMetadata(null, 'checkout_success', [
            'title' => 'Order Received - #' . $order->order_number,
            'robots' => 'noindex, nofollow',
        ]);

        return view('storefront.order_success', compact('order', 'whatsAppUrl', 'seo'));
    }
}

