<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\CartService;
use App\Services\DeliveryService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeliveryAndOrderTest extends TestCase
{
    use RefreshDatabase;

    protected DeliveryService $deliveryService;
    protected OrderService $orderService;
    protected CartService $cartService;
    protected User $admin;
    protected User $customer;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deliveryService = app(DeliveryService::class);
        $this->cartService = app(CartService::class);
        $this->orderService = app(OrderService::class);

        // Seed core delivery settings
        Setting::set('store_latitude', 3.139003, 'delivery');
        Setting::set('store_longitude', 101.686855, 'delivery');
        Setting::set('free_delivery_radius', 5, 'delivery');
        Setting::set('paid_delivery_radius', 2, 'delivery');
        Setting::set('paid_delivery_charge_mode', 'fixed', 'delivery');
        Setting::set('paid_delivery_charge', 15, 'delivery');
        Setting::set('free_floor_limit', 1, 'delivery');
        Setting::set('extra_floor_charge', 10, 'delivery');
        Setting::set('max_floor_allowed', 20, 'delivery');
        Setting::set('min_order_value', 150, 'delivery');
        Setting::set('require_advance_payment', true, 'payment');
        Setting::set('advance_percentage', 10, 'payment');
        Setting::set('currency_symbol', 'RM', 'general');

        // Seed Admin and Customer
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'is_active' => true,
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer@test.com',
            'is_active' => true,
        ]);

        // Seed Category and Bulk Product
        $category = Category::create([
            'name' => 'Beverages',
            'slug' => 'beverages',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'FANTA Orange 12x1.25L Wholesale Carton',
            'slug' => 'fanta-orange-12x125l-carton',
            'sku' => 'FANTA-12-1250',
            'pack_size' => '12 x 1.25L',
            'price' => 45.00,
            'sale_price' => 40.00,
            'stock_quantity' => 100,
            'is_active' => true,
        ]);
    }

    /**
     * Test Haversine distance calculation and Zone 1 (Free Delivery <= 5km)
     */
    public function test_zone_1_free_delivery_within_5km(): void
    {
        // Coordinate ~1.3 km from store (3.139003, 101.686855)
        $customerLat = 3.148000;
        $customerLng = 101.695000;

        $evaluation = $this->deliveryService->evaluateDelivery($customerLat, $customerLng, 0, 160.00);

        $this->assertTrue($evaluation['eligible']);
        $this->assertEquals('free', $evaluation['zone']);
        $this->assertEquals(0.00, $evaluation['delivery_charge']);
        $this->assertLessThanOrEqual(5.0, $evaluation['distance_km']);
    }

    /**
     * Test Zone 2 (Paid Delivery 5.01km - 7.00km)
     */
    public function test_zone_2_paid_delivery(): void
    {
        // Coordinate ~6.1 km from store
        $customerLat = 3.185000;
        $customerLng = 101.715000;

        $evaluation = $this->deliveryService->evaluateDelivery($customerLat, $customerLng, 0, 160.00);

        $this->assertTrue($evaluation['eligible']);
        $this->assertEquals('paid', $evaluation['zone']);
        $this->assertEquals(15.00, $evaluation['delivery_charge']);
        $this->assertGreaterThan(5.0, $evaluation['distance_km']);
        $this->assertLessThanOrEqual(7.0, $evaluation['distance_km']);
    }

    /**
     * Test Zone 3 (Outside coverage > 7km)
     */
    public function test_zone_3_outside_delivery_radius(): void
    {
        // Coordinate > 20 km away
        $customerLat = 3.010000;
        $customerLng = 101.500000;

        $evaluation = $this->deliveryService->evaluateDelivery($customerLat, $customerLng, 0, 160.00);

        $this->assertFalse($evaluation['eligible']);
        $this->assertEquals('unavailable', $evaluation['zone']);
    }

    /**
     * Test Floor Surcharge Calculation
     */
    public function test_floor_surcharge_calculation(): void
    {
        // Floor 0 / Floor 1 (within limit 1) -> fee = 0
        $fee1 = $this->deliveryService->calculateFloorCharge(1);
        $this->assertEquals(0.00, $fee1);

        // Floor 4 without lift (limit 1, fee RM10/floor) -> (4 - 1) * 10 = RM 30
        $fee4NoLift = $this->deliveryService->calculateFloorCharge(4);
        $this->assertEquals(30.00, $fee4NoLift);
    }

    /**
     * Test Server-Side Order Price Recalculation and Creation
     */
    public function test_order_creation_with_advance_deposit_and_proof(): void
    {
        Storage::fake('local');

        // Put 4 cartons into cart session @ RM 40.00 = RM 160.00 subtotal
        $this->cartService->add((int) $this->product->id, 4);

        $customerLat = 3.148000;
        $customerLng = 101.695000;
        $proofFile = UploadedFile::fake()->image('payment_receipt.jpg');

        $order = $this->orderService->createOrderWithProof(
            data: [
                'user_id' => $this->customer->id,
                'customer_name' => 'Wholesale Grocer Sdn Bhd',
                'customer_email' => 'grocer@example.com',
                'customer_phone' => '+60129876543',
                'delivery_address' => '123 Commercial Avenue, Level 3',
                'delivery_city' => 'Kuala Lumpur',
                'latitude' => $customerLat,
                'longitude' => $customerLng,
                'floor' => 3,
                'payment_reference' => 'DUITNOW-20261101-001',
                'order_notes' => 'Please call before delivery',
            ],
            paymentProof: $proofFile
        );

        $this->assertNotNull($order);
        $this->assertEquals(160.00, (float) $order->subtotal);
        $this->assertEquals(0.00, (float) $order->delivery_charge);
        $this->assertEquals(20.00, (float) $order->floor_charge); // (3 - 1) * 10
        $this->assertEquals(180.00, (float) $order->grand_total);
        $this->assertEquals(18.00, (float) $order->advance_amount); // 10% of RM 180
        $this->assertEquals(162.00, (float) $order->balance_amount);
        $this->assertEquals('pending_payment_verification', $order->order_status);
        $this->assertEquals('pending_verification', $order->payment_status);

        // Verify private disk storage of proof
        $this->assertNotNull($order->payment_proof_path);
        Storage::disk('local')->assertExists($order->payment_proof_path);
    }

    /**
     * Test Payment Verification decrements inventory and updates status
     */
    public function test_payment_verification_deducts_stock(): void
    {
        Storage::fake('local');

        $initialStock = $this->product->stock_quantity; // 100
        $this->cartService->add((int) $this->product->id, 5);

        $order = $this->orderService->createOrderWithProof(
            data: [
                'user_id' => $this->customer->id,
                'customer_name' => 'Mart 99',
                'customer_email' => 'mart99@example.com',
                'customer_phone' => '+60129876543',
                'delivery_address' => 'Ground Floor Store',
                'delivery_city' => 'Kuala Lumpur',
                'latitude' => 3.148000,
                'longitude' => 101.695000,
                'floor' => 0,
                'payment_reference' => 'TNG-PROOF-8899',
            ],
            paymentProof: UploadedFile::fake()->image('receipt.png')
        );

        // Admin verifies the advance payment
        $this->orderService->verifyPayment($order, $this->admin->id, 'Deposit verified via DuitNow');

        $order->refresh();
        $this->product->refresh();

        $this->assertEquals('verified', $order->payment_status);
        $this->assertEquals('confirmed', $order->order_status);
        $this->assertEquals($initialStock - 5, $this->product->stock_quantity); // 95
    }

    /**
     * Test Payment Rejection cancels order and records rejection reason
     */
    public function test_payment_rejection_marks_rejected(): void
    {
        Storage::fake('local');

        $this->cartService->add((int) $this->product->id, 4);

        $order = $this->orderService->createOrderWithProof(
            data: [
                'user_id' => $this->customer->id,
                'customer_name' => 'Buyer',
                'customer_email' => 'buyer@example.com',
                'customer_phone' => '+60129876543',
                'delivery_address' => 'Ground Floor Store',
                'delivery_city' => 'Kuala Lumpur',
                'latitude' => 3.148000,
                'longitude' => 101.695000,
                'floor' => 0,
                'payment_reference' => 'REF-FAKE-001',
            ],
            paymentProof: UploadedFile::fake()->image('fake.png')
        );

        $this->orderService->rejectPayment($order, $this->admin->id, 'Receipt amount does not match 10% deposit required.');

        $order->refresh();
        $this->assertEquals('rejected', $order->payment_status);
        $this->assertEquals('payment_rejected', $order->order_status);
        $this->assertStringContainsString('Receipt amount does not match', $order->payment_rejection_reason);
    }

    /**
     * Test Dynamic Placeholders in CMS Policy Pages (Phase 8)
     */
    public function test_policy_dynamic_placeholders(): void
    {
        Setting::set('site_name', '99BulkSales MY', 'general');
        Setting::set('admin_phone', '+601122334455', 'notification');

        $page = Page::create([
            'title' => 'Terms and Conditions',
            'slug' => 'terms-and-conditions',
            'content' => 'Welcome to {store_name}. For support, call {admin_phone}. Free delivery within {free_delivery_radius}.',
            'is_published' => true,
        ]);

        $rendered = $page->parsed_content;

        $this->assertStringContainsString('Welcome to 99BulkSales MY', $rendered);
        $this->assertStringContainsString('+601122334455', $rendered);
        $this->assertStringContainsString('Free delivery within 5 km', $rendered);
    }
}
