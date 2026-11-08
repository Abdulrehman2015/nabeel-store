<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Banner;
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
     * Resolve Google Maps or map link to latitude & longitude coordinates.
     */
    public function resolveMapLink(Request $request)
    {
        $input = trim((string) $request->input('url', ''));

        if (empty($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid map link or coordinates.'
            ], 422);
        }

        // 1. Direct coordinates check: "42.91542, -78.89305"
        if (preg_match('/^\s*(-?\d{1,2}(?:\.\d+)?)\s*,\s*(-?\d{1,3}(?:\.\d+)?)\s*$/', $input, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                return response()->json([
                    'success' => true,
                    'lat' => $lat,
                    'lng' => $lng,
                    'name' => 'Coordinates (' . $lat . ', ' . $lng . ')'
                ]);
            }
        }

        $url = $input;
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided text is not a valid URL or coordinate pair.'
            ], 422);
        }

        $lat = null;
        $lng = null;
        $placeName = null;

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_CONNECTTIMEOUT => 8,
                CURLOPT_TIMEOUT => 12,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                CURLOPT_HTTPHEADER => [
                    'Accept-Language: en-US,en;q=0.9',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                ],
            ]);
            $body = curl_exec($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url;
            curl_close($ch);

            // Extract place name if present in URL
            if (preg_match('#/maps/place/([^/@?]+)#i', $effectiveUrl, $pm)) {
                $placeName = rawurldecode(str_replace('+', ' ', $pm[1]));
            }

            // Priority A: Google Maps protobuf pin coordinates (!3d... !4d...)
            if (preg_match('/!3d(-?\d{1,2}(?:\.\d+)+)!4d(-?\d{1,3}(?:\.\d+)+)/', $effectiveUrl, $m) ||
                ($body && preg_match('/!3d(-?\d{1,2}(?:\.\d+)+)!4d(-?\d{1,3}(?:\.\d+)+)/', $body, $m))) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];
            }
            // Priority B: URL @lat,lng format
            elseif (preg_match('/@(-?\d{1,2}(?:\.\d+)+),(-?\d{1,3}(?:\.\d+)+)/', $effectiveUrl, $m)) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];
            }
            // Priority C: Query parameter ?q=lat,lng or ll=lat,lng
            elseif (preg_match('/[?&](?:q|query|ll|sll)=(-?\d{1,2}(?:\.\d+)+)(?:%2C|,)(-?\d{1,3}(?:\.\d+)+)/i', $effectiveUrl, $m) ||
                    ($body && preg_match('/[?&](?:q|query|ll|sll)=(-?\d{1,2}(?:\.\d+)+)(?:%2C|,)(-?\d{1,3}(?:\.\d+)+)/i', $body, $m))) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];
            }
            // Priority D: Static map center in HTML (center=lat%2Clng)
            elseif ($body && preg_match('/center=(-?\d{1,2}(?:\.\d+)+)(?:%2C|,)(-?\d{1,3}(?:\.\d+)+)/i', $body, $m)) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];
            }
            // Priority E: OpenStreetMap link format
            elseif (preg_match('#(?:#map=\d+/|mlat=)(-?\d{1,2}(?:\.\d+)+)(?:&mlon=|/)(-?\d{1,3}(?:\.\d+)+)#i', $effectiveUrl, $m)) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];
            }

            if ($lat !== null && $lng !== null && $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                return response()->json([
                    'success' => true,
                    'lat' => $lat,
                    'lng' => $lng,
                    'name' => $placeName ?: 'Resolved Location',
                    'effective_url' => $effectiveUrl,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not detect geographic coordinates from this link. Please ensure it is a valid Google Maps or map location URL, or drag the pin directly on the map.'
            ], 422);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resolve map link: ' . $e->getMessage()
            ], 500);
        }
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
            'store_name' => 'required|string|max:100',
            'site_logo' => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|image|max:1024',
        ]);

        $storeName = trim($validated['store_name']);
        Setting::set('store_name', $storeName, 'appearance');
        Setting::set('site_name', $storeName, 'appearance');

        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('theme', 'public');
            Setting::set('site_logo', $path, 'appearance');
        }
        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('theme', 'public');
            Setting::set('site_favicon', $path, 'appearance');
        }

        // If the main admin account currently has old store name, synchronize it as well
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin && (str_contains($admin->name, 'N&J') || str_contains($admin->name, 'N&I') || str_contains($admin->name, '99') || str_contains($admin->name, 'Store'))) {
            $admin->update(['name' => $storeName]);
        }

        ActivityLog::log('settings.appearance_updated');

        return redirect()->back()->with('success', 'Store name and brand identity updated successfully! Applied live everywhere across POS, invoices, storefront, and admin.');
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

    /**
     * Phase Homepage Settings: Hero Banners, Headings, Descriptions & Dynamic Add (+) Sliders
     */
    public function homepage()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.settings.homepage', compact('banners'));
    }

    public function saveHomepage(Request $request)
    {
        $request->validate([
            'banners' => 'nullable|array',
            'banners.*.title' => 'required|string|max:150',
            'banners.*.subtitle' => 'nullable|string|max:255',
            'banners.*.badge' => 'nullable|string|max:50',
            'banners.*.link' => 'nullable|string|max:255',
            'banners.*.button_text' => 'nullable|string|max:50',
            'banners.*.sort_order' => 'nullable|integer',
            'banners.*.desktop_image' => 'nullable|image|max:5120',

            'new_banners' => 'nullable|array',
            'new_banners.*.title' => 'required|string|max:150',
            'new_banners.*.subtitle' => 'nullable|string|max:255',
            'new_banners.*.badge' => 'nullable|string|max:50',
            'new_banners.*.link' => 'nullable|string|max:255',
            'new_banners.*.button_text' => 'nullable|string|max:50',
            'new_banners.*.sort_order' => 'nullable|integer',
            'new_banners.*.desktop_image' => 'nullable|image|max:5120',
        ]);

        // 1. Update existing banners
        if ($request->has('banners')) {
            foreach ($request->input('banners') as $id => $bannerData) {
                $banner = Banner::find($id);
                if (!$banner) continue;

                $banner->title = $bannerData['title'];
                $banner->subtitle = $bannerData['subtitle'] ?? null;
                $banner->badge = $bannerData['badge'] ?? null;
                $banner->link = $bannerData['link'] ?? null;
                $banner->button_text = $bannerData['button_text'] ?? null;
                $banner->sort_order = isset($bannerData['sort_order']) ? (int)$bannerData['sort_order'] : 0;
                $banner->is_active = isset($bannerData['is_active']) ? (bool)$bannerData['is_active'] : false;

                if ($request->hasFile("banners.{$id}.desktop_image")) {
                    $banner->desktop_image = $request->file("banners.{$id}.desktop_image")->store('banners', 'public');
                }

                $banner->save();
            }
        }

        // 2. Create newly added banners from dynamic "+" rows
        if ($request->has('new_banners')) {
            foreach ($request->input('new_banners') as $index => $newBannerData) {
                if (empty($newBannerData['title'])) continue;

                $imagePath = 'images/banner-1.jpg'; // default fallback
                if ($request->hasFile("new_banners.{$index}.desktop_image")) {
                    $imagePath = $request->file("new_banners.{$index}.desktop_image")->store('banners', 'public');
                }

                Banner::create([
                    'title' => $newBannerData['title'],
                    'subtitle' => $newBannerData['subtitle'] ?? null,
                    'badge' => $newBannerData['badge'] ?? null,
                    'link' => $newBannerData['link'] ?? null,
                    'button_text' => $newBannerData['button_text'] ?? 'Shop Now',
                    'sort_order' => isset($newBannerData['sort_order']) ? (int)$newBannerData['sort_order'] : (Banner::max('sort_order') + 1),
                    'is_active' => isset($newBannerData['is_active']) ? (bool)$newBannerData['is_active'] : true,
                    'desktop_image' => $imagePath,
                ]);
            }
        }

        if ($request->filled('slider_autoplay_speed')) {
            Setting::set('slider_autoplay_speed', $request->input('slider_autoplay_speed', 6000), 'homepage');
        }

        ActivityLog::log('settings.homepage_banners_updated');

        return redirect()->route('admin.settings.homepage')->with('success', 'Homepage banners and slider settings updated successfully!');
    }

    public function deleteBanner(Banner $banner)
    {
        ActivityLog::log('banner.deleted', $banner, ['title' => $banner->title]);
        $banner->delete();

        return redirect()->route('admin.settings.homepage')->with('success', 'Slide banner removed successfully.');
    }

    /**
     * Store Data Reset & Cleanup Tool
     */
    public function dataReset()
    {
        $counts = [
            'products' => \App\Models\Product::count(),
            'categories' => \App\Models\Category::count(),
            'brands' => \App\Models\Brand::count(),
            'orders' => \App\Models\Order::count(),
            'customers' => \App\Models\User::where('role', 'customer')->count(),
            'staff' => \App\Models\User::whereIn('role', ['admin', 'staff', 'cashier'])->count(),
        ];

        return view('admin.settings.data_reset', compact('counts'));
    }

    public function processDataReset(Request $request)
    {
        $request->validate([
            'confirm_text' => 'required|in:DELETE,delete,CONFIRM,confirm',
        ], [
            'confirm_text.in' => 'You must type DELETE to confirm data wipe.',
        ]);

        $deleted = [];

        if ($request->boolean('wipe_orders')) {
            $count = \App\Models\Order::count();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
            \Illuminate\Support\Facades\DB::table('order_items')->truncate();
            \Illuminate\Support\Facades\DB::table('order_histories')->truncate();
            \Illuminate\Support\Facades\DB::table('orders')->truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            $deleted[] = "{$count} Orders & POS Sales";
        }

        if ($request->boolean('wipe_products')) {
            $count = \App\Models\Product::count();
            \App\Models\Product::query()->each(function ($p) {
                if ($p->featured_image && !str_starts_with($p->featured_image, 'http')) {
                    @\Illuminate\Support\Facades\Storage::disk('public')->delete($p->featured_image);
                }
                $p->delete();
            });
            \Illuminate\Support\Facades\DB::table('products')->delete();
            $deleted[] = "{$count} Products";
        }

        if ($request->boolean('wipe_categories')) {
            $count = \App\Models\Category::count();
            \App\Models\Category::query()->each(function ($c) {
                if ($c->image && !str_starts_with($c->image, 'http')) {
                    @\Illuminate\Support\Facades\Storage::disk('public')->delete($c->image);
                }
                $c->delete();
            });
            \Illuminate\Support\Facades\DB::table('categories')->delete();
            $deleted[] = "{$count} Categories";
        }

        if ($request->boolean('wipe_brands')) {
            $count = \App\Models\Brand::count();
            \App\Models\Brand::query()->each(function ($b) {
                if ($b->logo && !str_starts_with($b->logo, 'http')) {
                    @\Illuminate\Support\Facades\Storage::disk('public')->delete($b->logo);
                }
                $b->delete();
            });
            \Illuminate\Support\Facades\DB::table('brands')->delete();
            $deleted[] = "{$count} Brands";
        }

        if ($request->boolean('wipe_customers')) {
            $customers = \App\Models\User::where('role', 'customer')
                ->where('id', '!=', auth()->id())
                ->get();
            $count = $customers->count();
            foreach ($customers as $c) {
                $c->delete();
            }
            $deleted[] = "{$count} Customers";
        }

        $summary = empty($deleted) ? 'No data categories were selected.' : implode(', ', $deleted) . ' permanently deleted.';

        ActivityLog::log('store.data_reset', null, ['summary' => $summary]);

        return redirect()->route('admin.data-reset')->with('success', "Store reset completed: {$summary}");
    }

    /**
     * One-Click Clean Slate: Complete Store Wipe (Keeps Admin & Staff Logins Intact)
     */
    public function cleanSlate(Request $request)
    {
        $request->validate([
            'confirm_wipe' => 'required|in:CLEAN,clean,DELETE,delete,RESET,reset',
        ], [
            'confirm_wipe.in' => 'Please type CLEAN or RESET to confirm the complete data wipe.',
        ]);

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        $tablesToClean = [
            'order_items',
            'order_histories',
            'orders',
            'product_images',
            'products',
            'categories',
            'brands',
            'customer_addresses',
            'otp_codes',
            'activity_logs',
        ];

        foreach ($tablesToClean as $table) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                \Illuminate\Support\Facades\DB::table($table)->truncate();
            }
        }

        // Delete all customer accounts (all admin, staff, and cashier logins are 100% kept)
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            \Illuminate\Support\Facades\DB::table('users')->where('role', 'customer')->delete();
        }

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        try {
            \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('products');
            \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('categories');
            \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('brands');
        } catch (\Throwable $e) {}

        $staffCount = \App\Models\User::whereIn('role', ['admin', 'staff', 'cashier'])->count();

        ActivityLog::log('store.clean_slate', null, [
            'executed_by' => auth()->user()->name,
            'preserved_staff_logins' => $staffCount,
        ]);

        return redirect()->route('admin.data-reset')
            ->with('success', "Complete Store Clean Slate Successful! All products, orders, categories, brands, and customer data wiped. All {$staffCount} Admin & Staff logins are intact.");
    }
}

