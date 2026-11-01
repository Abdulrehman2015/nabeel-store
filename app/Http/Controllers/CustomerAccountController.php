<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Services\NotificationService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class CustomerAccountController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SeoService $seoService
    ) {}

    public function orders()
    {
        $orders = auth()->user()->orders()->paginate(10);

        $seo = $this->seoService->resolveMetadata(null, 'account_orders', [
            'title' => 'My Bulk Orders',
            'robots' => 'noindex, nofollow',
        ]);

        return view('storefront.account.orders', compact('orders', 'seo'));
    }

    public function orderDetails(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items', 'histories'])
            ->firstOrFail();

        $whatsAppUrl = $this->notificationService->getWhatsAppClickUrl($order);

        $seo = $this->seoService->resolveMetadata(null, 'account_order_details', [
            'title' => 'Order Details #' . $order->order_number,
            'robots' => 'noindex, nofollow',
        ]);

        return view('storefront.account.order_details', compact('order', 'whatsAppUrl', 'seo'));
    }

    public function addresses()
    {
        $addresses = auth()->user()->addresses;

        $seo = $this->seoService->resolveMetadata(null, 'account_addresses', [
            'title' => 'Saved Delivery Addresses',
            'robots' => 'noindex, nofollow',
        ]);

        return view('storefront.account.addresses', compact('addresses', 'seo'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:25',
            'address_line' => 'required|string|max:300',
            'area_city' => 'required|string|max:100',
            'floor' => 'required|integer|min:0',
            'landmark' => 'nullable|string|max:150',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        auth()->user()->addresses()->create($request->all());

        return redirect()->back()->with('success', 'Address saved successfully.');
    }

    public function deleteAddress(int $id)
    {
        CustomerAddress::where('id', $id)->where('user_id', auth()->id())->delete();
        return redirect()->back()->with('success', 'Address deleted.');
    }

    /**
     * Download customer personal data as JSON (Phase 8 compliance).
     */
    public function exportData()
    {
        $user = auth()->user()->load(['orders.items', 'addresses']);

        $data = [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at,
            ],
            'saved_addresses' => $user->addresses,
            'orders' => $user->orders->map(function ($order) {
                return [
                    'order_number' => $order->order_number,
                    'order_status' => $order->order_status,
                    'grand_total' => $order->grand_total,
                    'advance_amount' => $order->advance_amount,
                    'delivery_address' => $order->delivery_address,
                    'floor' => $order->floor,
                    'created_at' => $order->created_at,
                    'items' => $order->items->map(fn($i) => [
                        'product_name' => $i->product_name,
                        'pack_size' => $i->pack_size,
                        'unit_price' => $i->unit_price,
                        'quantity' => $i->quantity,
                        'line_total' => $i->line_total,
                    ]),
                ];
            }),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $filename = 'customer-data-' . auth()->id() . '-' . date('Y-m-d') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}

