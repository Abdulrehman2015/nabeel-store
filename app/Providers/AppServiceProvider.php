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

                if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'cashier_id')) {
                        \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                            $table->foreignId('cashier_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
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

        // Auto-seed initial 5 dummy products if the store catalog on live server is empty
        try {
            $dummyFlag = storage_path('framework/cache/dummy_products_seeded_v1.flag');
            if (!file_exists($dummyFlag)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('products') && \App\Models\Product::count() === 0) {
                    if (!class_exists(\Database\Seeders\DummyProductSeeder::class) && file_exists(database_path('seeders/DummyProductSeeder.php'))) {
                        require_once database_path('seeders/DummyProductSeeder.php');
                    }
                    if (class_exists(\Database\Seeders\DummyProductSeeder::class)) {
                        (new \Database\Seeders\DummyProductSeeder())->run();
                    }
                }
                @file_put_contents($dummyFlag, date('Y-m-d H:i:s'));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Dummy products auto-seed notice: ' . $e->getMessage());
        }
    }
}
