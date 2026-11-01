<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General & Store
            ['key' => 'site_name', 'value' => '99 Bulk Sales', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_tagline', 'value' => 'Wholesale Carton Groceries & Household Essentials', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_email', 'value' => 'orders@99bulksales.my', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_phone', 'value' => '+60 12-889 9900', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_address', 'value' => 'Lot 45, Jalan Industri 3/1, Kawasan Perindustrian Puchong, 47100 Puchong, Selangor', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_registration_no', 'value' => '202401019988 (1458923-M)', 'group' => 'general', 'type' => 'text'],
            ['key' => 'working_hours', 'value' => 'Mon - Sat: 8:00 AM - 7:00 PM (Closed on Sundays)', 'group' => 'general', 'type' => 'text'],
            ['key' => 'currency_symbol', 'value' => 'RM', 'group' => 'general', 'type' => 'text'],
            ['key' => 'currency_code', 'value' => 'MYR', 'group' => 'general', 'type' => 'text'],
            ['key' => 'currency_decimals', 'value' => '2', 'group' => 'general', 'type' => 'number'],
            ['key' => 'order_number_prefix', 'value' => 'BS', 'group' => 'general', 'type' => 'text'],
            ['key' => 'return_window_days', 'value' => '7', 'group' => 'general', 'type' => 'number'],
            ['key' => 'order_cutoff_time', 'value' => '4:00 PM', 'group' => 'general', 'type' => 'text'],

            // Phase 3: Delivery Settings
            ['key' => 'delivery_enabled', 'value' => '1', 'group' => 'delivery', 'type' => 'boolean'],
            ['key' => 'store_latitude', 'value' => '3.0245', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'store_longitude', 'value' => '101.6166', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'free_delivery_radius', 'value' => '5', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'paid_delivery_radius', 'value' => '2', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'paid_delivery_charge_mode', 'value' => 'fixed', 'group' => 'delivery', 'type' => 'select'], // fixed or per_km
            ['key' => 'paid_delivery_charge', 'value' => '10.00', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'free_floor_limit', 'value' => '1', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'extra_floor_charge', 'value' => '10.00', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'max_floor_allowed', 'value' => '15', 'group' => 'delivery', 'type' => 'number'],
            ['key' => 'min_order_value', 'value' => '100.00', 'group' => 'delivery', 'type' => 'number'],

            // Phase 3: Payment Settings (QR Advance)
            ['key' => 'require_advance_payment', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'advance_percentage', 'value' => '10', 'group' => 'payment', 'type' => 'number'],
            ['key' => 'qr_code_image', 'value' => 'images/demo-qr.png', 'group' => 'payment', 'type' => 'image'],
            ['key' => 'bank_account_details', 'value' => "Bank: Maybank Berhad\nAccount Name: 99 Bulk Sales Sdn Bhd\nAccount No: 5148 7921 6890\nDuitNow QR ID: 202401019988", 'group' => 'payment', 'type' => 'textarea'],
            ['key' => 'payment_instructions', 'value' => "1. Scan the DuitNow QR code or transfer to our Maybank account.\n2. Pay the exact Advance Deposit shown below.\n3. Take a screenshot of the completed transfer.\n4. Enter the Transaction/Reference ID and upload the screenshot to confirm your order.", 'group' => 'payment', 'type' => 'textarea'],

            // Phase 4: Notification Settings
            ['key' => 'notify_email_enabled', 'value' => '1', 'group' => 'notification', 'type' => 'boolean'],
            ['key' => 'admin_email', 'value' => 'admin@99bulksales.my', 'group' => 'notification', 'type' => 'text'],
            ['key' => 'admin_phone', 'value' => '+60128899900', 'group' => 'notification', 'type' => 'text'],
            ['key' => 'notify_telegram_enabled', 'value' => '0', 'group' => 'notification', 'type' => 'boolean'],
            ['key' => 'telegram_bot_token', 'value' => '', 'group' => 'notification', 'type' => 'text'],
            ['key' => 'telegram_chat_id', 'value' => '', 'group' => 'notification', 'type' => 'text'],
            ['key' => 'notify_callmebot_enabled', 'value' => '0', 'group' => 'notification', 'type' => 'boolean'],
            ['key' => 'callmebot_phone', 'value' => '', 'group' => 'notification', 'type' => 'text'],
            ['key' => 'callmebot_api_key', 'value' => '', 'group' => 'notification', 'type' => 'text'],

            // Phase 5: Master SEO Settings
            ['key' => 'seo_meta_title_template', 'value' => '%title% | %site_name%', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_default_title', 'value' => 'Wholesale Carton Bulk Sales & Household Supplies', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_default_description', 'value' => 'Shop groceries, drinks, canned food, cooking oil, detergent and paper goods in wholesale cartons with fast delivery direct to your doorstep.', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'seo_default_keywords', 'value' => 'bulk groceries, wholesale carton drinks, fanta 12x1.25L, 100plus bulk, milo carton, cooking oil bulk, wholesale grocery puchong', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_default_robots', 'value' => 'index, follow', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'google_search_console_tag', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'bing_verification_tag', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'facebook_pixel_id', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'custom_header_scripts', 'value' => '', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'custom_footer_scripts', 'value' => '', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'robots_txt_content', 'value' => "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /account/\nDisallow: /checkout/\n\nSitemap: " . url('/sitemap.xml'), 'group' => 'seo', 'type' => 'textarea'],

            // Phase 6: Dynamic Theme / Color Scheme
            ['key' => 'color_primary', 'value' => '#0284c7', 'group' => 'appearance', 'type' => 'color'], // Sky 600
            ['key' => 'color_primary_hover', 'value' => '#0369a1', 'group' => 'appearance', 'type' => 'color'],
            ['key' => 'color_secondary', 'value' => '#0f172a', 'group' => 'appearance', 'type' => 'color'], // Slate 900
            ['key' => 'color_accent', 'value' => '#f59e0b', 'group' => 'appearance', 'type' => 'color'], // Amber 500
            ['key' => 'color_bg', 'value' => '#f8fafc', 'group' => 'appearance', 'type' => 'color'], // Slate 50
            ['key' => 'color_text', 'value' => '#1e293b', 'group' => 'appearance', 'type' => 'color'], // Slate 800
            ['key' => 'color_header_bg', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color'],
            ['key' => 'color_footer_bg', 'value' => '#0f172a', 'group' => 'appearance', 'type' => 'color'],
            ['key' => 'color_button_text', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color'],
            ['key' => 'font_family', 'value' => 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif', 'group' => 'appearance', 'type' => 'select'],
            ['key' => 'border_radius', 'value' => '0.5rem', 'group' => 'appearance', 'type' => 'text'],

            // Phase 8: Cookie Consent
            ['key' => 'cookie_consent_enabled', 'value' => '1', 'group' => 'appearance', 'type' => 'boolean'],
            ['key' => 'cookie_consent_title', 'value' => 'We value your privacy', 'group' => 'appearance', 'type' => 'text'],
            ['key' => 'cookie_consent_message', 'value' => 'We use essential cookies to keep your shopping cart and order status secure. We only load analytics if you consent.', 'group' => 'appearance', 'type' => 'textarea'],
        ];

        foreach ($settings as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value'], 'group' => $item['group'], 'type' => $item['type']]
            );
        }
    }
}

