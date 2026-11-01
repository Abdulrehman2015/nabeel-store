<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Services\NotificationService;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $s = trim($request->query('search'));
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'LIKE', "%{$s}%")
                    ->orWhere('customer_name', 'LIKE', "%{$s}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$s}%")
                    ->orWhere('payment_reference', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->query('date'));
        }

        $orders = $query->paginate(15)->withQueryString();

        $counts = [
            'pending_payment_verification' => Order::where('order_status', 'pending_payment_verification')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'out_for_delivery' => Order::where('order_status', 'out_for_delivery')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'histories.user', 'verifiedBy']);
        $whatsAppUrl = $this->notificationService->getWhatsAppClickUrl($order);

        return view('admin.orders.show', compact('order', 'whatsAppUrl'));
    }

    /**
     * Admin approves/verifies the advance payment.
     * Order becomes 'confirmed' and stock is officially decremented.
     */
    public function verifyPayment(Request $request, Order $order)
    {
        $request->validate(['note' => 'nullable|string|max:500']);

        try {
            $this->orderService->verifyPayment($order, auth()->id(), $request->input('note'));
            $this->notificationService->sendStatusUpdateEmail($order);

            return redirect()->back()->with('success', 'Advance payment verified! Order #' . $order->order_number . ' is now Confirmed and warehouse stock is reserved.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin rejects the advance payment screenshot.
     */
    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|min:4|max:500',
        ]);

        try {
            $this->orderService->rejectPayment($order, auth()->id(), $request->input('reason'));
            $this->notificationService->sendStatusUpdateEmail($order);

            return redirect()->back()->with('success', 'Payment proof marked as rejected. Customer has been alerted.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Change operational status (Processing, Out for Delivery, Delivered, Cancelled).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,processing,out_for_delivery,delivered,cancelled',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->orderService->updateStatus($order, $request->input('status'), auth()->id(), $request->input('comment'));
            $this->notificationService->sendStatusUpdateEmail($order);

            return redirect()->back()->with('success', 'Order status updated to ' . ucfirst(str_replace('_', ' ', $request->input('status'))) . '.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add administrative note to order.
     */
    public function addInternalNote(Request $request, Order $order)
    {
        $request->validate(['internal_note' => 'required|string|max:1000']);

        $note = '[' . now()->format('Y-m-d H:i') . ' by ' . auth()->user()->name . ']: ' . trim($request->input('internal_note'));
        $newNotes = $order->internal_notes ? $order->internal_notes . "\n" . $note : $note;

        $order->update(['internal_notes' => $newNotes]);
        ActivityLog::log('order.note_added', $order, ['note' => $note]);

        return redirect()->back()->with('success', 'Internal note recorded.');
    }

    /**
     * Print PDF Invoice using Barryvdh DomPDF.
     */
    public function printInvoice(Order $order)
    {
        $order->load(['items', 'verifiedBy']);

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        ActivityLog::log('order.invoice_printed', $order);

        return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
    }

    /**
     * Serve private payment screenshot securely through authorized admin route (Phase 7.1).
     */
    public function viewPaymentProof(Request $request, Order $order)
    {
        if (!auth()->check() || !auth()->user()->isStaff()) {
            abort(403, 'Unauthorized access to payment proof.');
        }

        if (!$order->payment_proof_path || !Storage::disk('local')->exists($order->payment_proof_path)) {
            abort(404, 'Payment proof file not found.');
        }

        return Storage::disk('local')->response($order->payment_proof_path);
    }
}
