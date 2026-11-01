<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    /**
     * Phase 3.1: Delivery Settings
     */
    public function delivery()
    {
        return view('admin.settings.delivery');
    }

    public function saveDelivery(Request $request)
    {
        $validated = $request->validate([
            'delivery_enabled' => 'nullable|boolean',
            'store_latitude' => 'required|numeric|between:-90,90',
            'store_longitude' => 'required|numeric|between:-180,180',
            'free_delivery_radius' => 'required|numeric|min:0|max:500',
            'paid_delivery_radius' => 'required|numeric|min:0|max:500',
            'paid_delivery_charge_mode' => 'required|in:fixed,per_km',
            'paid_delivery_charge' => 'required|numeric|min:0',
            'free_floor_limit' => 'required|integer|min:0|max:50',
            'extra_floor_charge' => 'required|numeric|min:0',
            'max_floor_allowed' => 'required|integer|min:1|max:100',
            'min_order_value' => 'required|numeric|min:0',
            'currency_symbol' => 'required|string|max:10',
            'currency_decimals' => 'required|integer|in:0,2',
        ]);

        $validated['delivery_enabled'] = $request->boolean('delivery_enabled');

        Setting::setMany($validated, 'delivery');
        ActivityLog::log('settings.delivery_updated');

        return redirect()->back()->with('success', 'Delivery settings and zone parameters saved successfully.');
    }

    /**
     * Phase 3.6: Payment Settings
     */
    public function payment()
    {
        return view('admin.settings.payment');
    }

    public function savePayment(Request $request)
    {
        $validated = $request->validate([
            'require_advance_payment' => 'nullable|boolean',
            'advance_percentage' => 'required|numeric|min:1|max:100',
            'qr_code_image' => 'nullable|image|max:3072',
            'bank_account_details' => 'required|string|max:1000',
            'payment_instructions' => 'required|string|max:1000',
        ]);

        $validated['require_advance_payment'] = $request->boolean('require_advance_payment');

        if ($request->hasFile('qr_code_image')) {
            $path = $request->file('qr_code_image')->store('payment', 'public');
            $validated['qr_code_image'] = 'storage/' . $path;
        }

        Setting::setMany($validated, 'payment');
        ActivityLog::log('settings.payment_updated');

        return redirect()->back()->with('success', 'Payment and QR advance settings saved.');
    }

    /**
     * Phase 4: Notification Settings
     */
    public function notifications()
    {
        return view('admin.settings.notifications');
    }

    public function saveNotifications(Request $request)
    {
        $validated = $request->validate([
            'notify_email_enabled' => 'nullable|boolean',
            'admin_email' => 'required|email|max:150',
            'admin_phone' => 'required|string|max:30',
            'notify_telegram_enabled' => 'nullable|boolean',
            'telegram_bot_token' => 'nullable|string|max:150',
            'telegram_chat_id' => 'nullable|string|max:150',
            'notify_callmebot_enabled' => 'nullable|boolean',
            'callmebot_phone' => 'nullable|string|max:30',
            'callmebot_api_key' => 'nullable|string|max:100',
        ]);

        $validated['notify_email_enabled'] = $request->boolean('notify_email_enabled');
        $validated['notify_telegram_enabled'] = $request->boolean('notify_telegram_enabled');
        $validated['notify_callmebot_enabled'] = $request->boolean('notify_callmebot_enabled');

        Setting::setMany($validated, 'notification');
        ActivityLog::log('settings.notifications_updated');

        return redirect()->back()->with('success', 'Notification channels and credentials saved.');
    }

    /**
     * Phase 6: Appearance / Theme Settings
     */
    public function appearance()
    {
        return view('admin.settings.appearance');
    }

    public function saveAppearance(Request $request)
    {
        $validated = $request->validate([
            'color_primary' => 'required|string|max:20',
            'color_primary_hover' => 'required|string|max:20',
            'color_secondary' => 'required|string|max:20',
            'color_accent' => 'required|string|max:20',
            'color_bg' => 'required|string|max:20',
            'color_text' => 'required|string|max:20',
            'color_header_bg' => 'required|string|max:20',
            'color_footer_bg' => 'required|string|max:20',
            'color_button_text' => 'required|string|max:20',
            'font_family' => 'required|string|max:150',
            'border_radius' => 'required|string|max:20',
            'site_logo' => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('site_logo')) {
            $validated['site_logo'] = $request->file('site_logo')->store('theme', 'public');
        }
        if ($request->hasFile('site_favicon')) {
            $validated['site_favicon'] = $request->file('site_favicon')->store('theme', 'public');
        }

        Setting::setMany($validated, 'appearance');
        ActivityLog::log('settings.appearance_updated');

        return redirect()->back()->with('success', 'Theme and styling updated. Applied live across the store.');
    }

    /**
     * Phase 5.2: Master SEO Settings
     */
    public function masterSeo()
    {
        return view('admin.settings.master_seo');
    }

    public function saveMasterSeo(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:150',
            'seo_meta_title_template' => 'required|string|max:100',
            'seo_default_title' => 'required|string|max:150',
            'seo_default_description' => 'required|string|max:300',
            'seo_default_keywords' => 'nullable|string|max:300',
            'seo_default_robots' => 'required|string|max:50',
            'google_search_console_tag' => 'nullable|string|max:150',
            'bing_verification_tag' => 'nullable|string|max:150',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'robots_txt_content' => 'nullable|string',
            'custom_header_scripts' => 'nullable|string',
            'custom_footer_scripts' => 'nullable|string',
        ]);

        Setting::setMany($validated, 'seo');
        ActivityLog::log('settings.master_seo_updated');

        return redirect()->back()->with('success', 'Master SEO parameters and global scripts saved.');
    }

    /**
     * Phase 5.3: Page SEO Module
     */
    public function pageSeo(Request $request)
    {
        $selectedPage = $request->query('page', 'home');

        $availablePages = [
            'home' => 'Home Page',
            'categories' => 'All Categories Index',
            'cart' => 'Shopping Cart',
            'checkout' => 'Checkout Page',
            'about' => 'About Us',
            'contact' => 'Contact Us',
            'faqs' => 'FAQs Page',
            'track_order' => 'Order Tracking Page',
            'login' => 'Customer Login',
            'register' => 'Customer Register',
        ];

        // Also add CMS policies
        foreach (Page::all() as $p) {
            $availablePages[$p->slug] = $p->title . ' (Policy)';
        }

        $savedData = setting('page_seo_' . $selectedPage);
        if ($savedData && is_string($savedData)) {
            $savedData = json_decode($savedData, true);
        }

        return view('admin.settings.page_seo', compact('availablePages', 'selectedPage', 'savedData'));
    }

    public function savePageSeo(Request $request)
    {
        $request->validate([
            'page_key' => 'required|string',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:50',
            'schema_type' => 'nullable|string|max:50',
        ]);

        $pageKey = $request->input('page_key');
        $seoData = [
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'canonical_url' => $request->input('canonical_url'),
            'robots' => $request->input('robots', 'index, follow'),
            'schema_type' => $request->input('schema_type', 'WebPage'),
        ];

        Setting::set('page_seo_' . $pageKey, $seoData, 'seo', 'json');
        ActivityLog::log('settings.page_seo_updated', null, ['page' => $pageKey]);

        return redirect()->route('admin.settings.page-seo', ['page' => $pageKey])->with('success', 'Page SEO updated for ' . $pageKey . '.');
    }
}
