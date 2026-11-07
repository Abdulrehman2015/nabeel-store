<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            request()->header('x-forwarded-proto') === 'https' ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            str_contains(request()->getHost(), 'lhr.life') ||
            str_contains(request()->getHost(), 'ngrok') ||
            str_contains(request()->getHost(), 'trycloudflare') ||
            str_contains(request()->getHost(), 'devtunnels.ms') ||
            str_contains(request()->getHost(), 'app.github.dev')
        ) {
            URL::forceScheme('https');
        }

        // Auto-migrate missing columns for shared hosting environments (InfinityFree) where CLI migrate cannot run
        try {
            $flagFile = storage_path('framework/cache/schema_synced.flag');
            if (!file_exists($flagFile)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('products', 'purchase_price')) {
                        \Illuminate\Support\Facades\Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                            $table->decimal('purchase_price', 10, 2)->default(0.00)->after('price');
                        });
                        \Illuminate\Support\Facades\DB::table('products')
                            ->whereNull('purchase_price')
                            ->orWhere('purchase_price', '<=', 0)
                            ->update(['purchase_price' => \Illuminate\Support\Facades\DB::raw('ROUND(price * 0.70, 2)')]);
                    }

                    if (!\Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold')) {
                        \Illuminate\Support\Facades\Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                            $table->integer('low_stock_threshold')->default(10)->after('stock_quantity');
                        });
                    }
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    \Illuminate\Support\Facades\DB::table('settings')
                        ->where('key', 'store_address')
                        ->where('value', 'LIKE', '%Puchong%')
                        ->update(['value' => "34, Jalan Perkasa 3, Taman Ungku Tun Aminah, 81300 Skudai, Johor Darul Ta'zim, Malaysia"]);

                    \Illuminate\Support\Facades\DB::table('settings')
                        ->where('key', 'store_phone')
                        ->where(function($q) {
                            $q->where('value', 'LIKE', '%859%')
                              ->orWhere('value', 'LIKE', '%889%');
                        })
                        ->update(['value' => '+60 11-6412 9173']);
                }
                @file_put_contents($flagFile, 'synced');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Auto-migration column notice: ' . $e->getMessage());
        }

        // One-time store data wipe on live server (removes demo products, orders, categories, brands, customers)
        try {
            $wipeFlag = storage_path('framework/cache/store_data_wiped_v1.flag');
            if (!file_exists($wipeFlag)) {
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
                $truncateTables = [
                    'order_items',
                    'order_histories',
                    'orders',
                    'product_images',
                    'products',
                    'categories',
                    'brands',
                    'customer_addresses',
                    'otp_codes',
                    'notification_logs',
                    'activity_logs',
                    'jobs',
                    'failed_jobs',
                ];
                foreach ($truncateTables as $table) {
                    if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                        \Illuminate\Support\Facades\DB::table($table)->truncate();
                    }
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    \Illuminate\Support\Facades\DB::table('users')->where('role', 'customer')->delete();
                }
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

                @file_put_contents($wipeFlag, date('Y-m-d H:i:s'));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Store wipe notice: ' . $e->getMessage());
        }
    }
}
