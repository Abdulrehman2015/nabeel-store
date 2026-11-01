<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\NotificationService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SeoService $seoService
    ) {}

    public function index(Request $request)
    {
        $order = null;
        $searched = false;

        if ($request->filled('order_number') && $request->filled('phone')) {
            $searched = true;
            $orderNumber = trim($request->input('order_number'));
            $phone = trim($request->input('phone'));

            // Clean phone numbers for flexible match
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

            $order = Order::where('order_number', $orderNumber)
                ->where(function ($q) use ($phone, $cleanPhone) {
                    $q->where('customer_phone', $phone)
                        ->orWhere('customer_phone', 'LIKE', "%{$cleanPhone}%");
                })
                ->with(['items', 'histories'])
                ->first();
        }

        $whatsAppUrl = $order ? $this->notificationService->getWhatsAppClickUrl($order) : null;

        $seo = $this->seoService->resolveMetadata(null, 'track_order', [
            'title' => 'Live Order Tracking',
            'description' => 'Track your wholesale bulk carton order progress and delivery schedule.',
        ]);

        return view('storefront.order_track', compact('order', 'searched', 'whatsAppUrl', 'seo'));
    }
}
