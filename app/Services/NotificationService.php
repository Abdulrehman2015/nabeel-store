<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Dispatch all active notifications for a newly created order.
     */
    public function sendNewOrderNotifications(Order $order): void
    {
        // 1. Telegram Bot Notification
        if (setting('notify_telegram_enabled', false)) {
            $this->sendTelegramNotification($order);
        }

        // 2. CallMeBot WhatsApp Notification
        if (setting('notify_callmebot_enabled', false)) {
            $this->sendCallMeBotNotification($order);
        }

        // 3. Email Notifications (Queue Mailables)
        if (setting('notify_email_enabled', true)) {
            $this->sendOrderEmails($order);
        }
    }

    /**
     * Send Telegram message using Bot API (100% Free).
     */
    public function sendTelegramNotification(Order $order): bool
    {
        $botToken = setting('telegram_bot_token');
        $chatId = setting('telegram_chat_id');

        if (empty($botToken) || empty($chatId)) {
            $this->logNotification($order->id, 'telegram', $chatId ?? 'unconfigured', 'Telegram token or chat ID is missing.', 'skipped', 'Missing credentials');
            return false;
        }

        $adminOrderUrl = url('/admin/orders/' . $order->id);
        $message = "🛒 *NEW ORDER RECEIVED!*\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "📦 *Order Number:* `{$order->order_number}`\n"
            . "👤 *Customer:* {$order->customer_name}\n"
            . "📞 *Phone:* {$order->customer_phone}\n"
            . "📍 *Address:* {$order->delivery_address}, {$order->delivery_city}\n"
            . "🏢 *Floor:* Floor {$order->floor}\n"
            . "🚚 *Delivery Zone:* " . strtoupper($order->delivery_zone) . " ({$order->distance_km} km)\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "💵 *Total Amount:* " . format_currency($order->grand_total) . "\n"
            . "💳 *Advance Paid:* " . format_currency($order->advance_amount) . " (Ref: `{$order->payment_reference}`)\n"
            . "💰 *Balance on Delivery:* " . format_currency($order->balance_amount) . "\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "🔗 [View Order & Verify Payment]({$adminOrderUrl})";

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => false,
            ]);

            if ($response->successful()) {
                $this->logNotification($order->id, 'telegram', $chatId, $message, 'success');
                return true;
            } else {
                $this->logNotification($order->id, 'telegram', $chatId, $message, 'failed', $response->body());
                return false;
            }
        } catch (\Throwable $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            $this->logNotification($order->id, 'telegram', $chatId, $message, 'failed', $e->getMessage());
            return false;
        }
    }

    /**
     * Send CallMeBot WhatsApp HTTP Notification (Free).
     */
    public function sendCallMeBotNotification(Order $order): bool
    {
        $phone = setting('callmebot_phone');
        $apiKey = setting('callmebot_api_key');

        if (empty($phone) || empty($apiKey)) {
            $this->logNotification($order->id, 'callmebot', $phone ?? 'unconfigured', 'CallMeBot unconfigured', 'skipped', 'Missing credentials');
            return false;
        }

        $text = store_name() . " - New Order #{$order->order_number}\n"
            . "Customer: {$order->customer_name} ({$order->customer_phone})\n"
            . "Total: " . format_currency($order->grand_total) . "\n"
            . "Advance: " . format_currency($order->advance_amount) . " (Ref: {$order->payment_reference})\n"
            . "Floor: {$order->floor}\n"
            . "View: " . url('/admin/orders/' . $order->id);

        try {
            $url = "https://api.callmebot.com/whatsapp.php?phone=" . urlencode($phone) . "&text=" . urlencode($text) . "&apikey=" . urlencode($apiKey);
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $this->logNotification($order->id, 'callmebot', $phone, $text, 'success');
                return true;
            } else {
                $this->logNotification($order->id, 'callmebot', $phone, $text, 'failed', $response->body());
                return false;
            }
        } catch (\Throwable $e) {
            Log::error('CallMeBot notification failed: ' . $e->getMessage());
            $this->logNotification($order->id, 'callmebot', $phone, $text, 'failed', $e->getMessage());
            return false;
        }
    }

    /**
     * Generate WhatsApp Click-to-Chat fallback link.
     */
    public function getWhatsAppClickUrl(Order $order): string
    {
        $adminPhone = preg_replace('/[^0-9]/', '', setting('admin_phone', '+60123456789'));
        $text = "Hello Admin, here are the details for Order #{$order->order_number}:\n"
            . "Customer: {$order->customer_name} ({$order->customer_phone})\n"
            . "Total: " . format_currency($order->grand_total) . "\n"
            . "Advance: " . format_currency($order->advance_amount) . "\n"
            . "Reference: {$order->payment_reference}\n"
            . "Admin link: " . url('/admin/orders/' . $order->id);

        return "https://wa.me/{$adminPhone}?text=" . rawurlencode($text);
    }

    /**
     * Send customer order confirmation and admin alert emails.
     */
    public function sendOrderEmails(Order $order): void
    {
        try {
            // Customer confirmation email
            if (!empty($order->customer_email)) {
                Mail::to($order->customer_email)->queue(new \App\Mail\OrderPlacedCustomerMail($order));
                $this->logNotification($order->id, 'email', $order->customer_email, 'Order confirmation email sent to customer.', 'success');
            }

            // Admin alert email
            $adminEmail = setting('admin_email', config('mail.from.address'));
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->queue(new \App\Mail\OrderAlertAdminMail($order));
                $this->logNotification($order->id, 'email', $adminEmail, 'Order alert email sent to admin.', 'success');
            }
        } catch (\Throwable $e) {
            Log::error('Order email sending failed: ' . $e->getMessage());
            $this->logNotification($order->id, 'email', $order->customer_email, 'Email failed', 'failed', $e->getMessage());
        }
    }

    /**
     * Send status update email to customer.
     */
    public function sendStatusUpdateEmail(Order $order): void
    {
        try {
            if (!empty($order->customer_email)) {
                Mail::to($order->customer_email)->queue(new \App\Mail\OrderStatusUpdatedMail($order));
                $this->logNotification($order->id, 'email', $order->customer_email, 'Status update to ' . $order->order_status, 'success');
            }
        } catch (\Throwable $e) {
            Log::error('Status update email failed: ' . $e->getMessage());
        }
    }

    /**
     * Log notification outcome into database.
     */
    protected function logNotification(?int $orderId, string $channel, string $recipient, string $message, string $status, ?string $error = null): void
    {
        try {
            NotificationLog::create([
                'order_id' => $orderId,
                'channel' => $channel,
                'recipient' => $recipient,
                'message_preview' => substr($message, 0, 500),
                'status' => $status,
                'error_message' => $error,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Could not log notification: ' . $e->getMessage());
        }
    }
}

