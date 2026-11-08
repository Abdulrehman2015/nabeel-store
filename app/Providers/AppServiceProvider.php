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
            str_contains(request()->getHost(), 'nabeel.site.je') ||
            str_contains(request()->getHost(), 'site.je') ||
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
            $flagFile = storage_path('framework/cache/schema_synced_v7.flag');
            if (!file_exists($flagFile)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` MODIFY `pack_size` VARCHAR(255) NULL DEFAULT NULL");
                    } catch (\Throwable $e) {}
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` MODIFY `unit_description` VARCHAR(255) NULL DEFAULT NULL");
                    } catch (\Throwable $e) {}
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` MODIFY `weight_volume` VARCHAR(255) NULL DEFAULT NULL");
                    } catch (\Throwable $e) {}

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
                            $table->unsignedBigInteger('cashier_id')->nullable()->after('user_id');
                            $table->index('cashier_id');
                        });
                    }
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('order_items')) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('order_items', 'original_price')) {
                        \Illuminate\Support\Facades\Schema::table('order_items', function (\Illuminate\Database\Schema\Blueprint $table) {
                            $table->decimal('original_price', 10, 2)->nullable()->after('unit_price');
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

                    $existingStoreName = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'store_name')->value('value');
                    if (!$existingStoreName) {
                        $siteName = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'site_name')->value('value') ?: 'N&J Mini Mart';
                        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                            ['key' => 'store_name'],
                            ['value' => $siteName, 'group' => 'appearance', 'created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
                if (!\Illuminate\Support\Facades\Schema::hasTable('admin_notifications')) {
                    \Illuminate\Support\Facades\Schema::create('admin_notifications', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->id();
                        $table->string('type', 40)->default('system');
                        $table->string('level', 20)->default('info');
                        $table->string('title');
                        $table->text('message');
                        $table->string('url')->nullable();
                        $table->string('action_text', 50)->nullable();
                        $table->json('data')->nullable();
                        $table->boolean('is_read')->default(false);
                        $table->timestamp('read_at')->nullable();
                        $table->timestamps();

                        $table->index('type');
                        $table->index('is_read');
                        $table->index('created_at');
                    });
                }

                @file_put_contents($flagFile, 'synced');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Auto-migration column notice: ' . $e->getMessage());
        }

        // Automatic table ensure and 30-day retention cleanup for notifications & expenses
        try {
            \App\Models\AdminNotification::ensureTableExists();
            \App\Models\ProductExpense::ensureTableExists();
            if (\Illuminate\Support\Facades\Schema::hasTable('admin_notifications')) {
                \App\Models\AdminNotification::purgeExpired(30);
            }
        } catch (\Throwable $e) {}

        // One-time live database fresh reset: wipe all test catalog, orders, and customer data while strictly keeping admin intact
        try {
            $freshFlag = storage_path('framework/cache/db_fresh_slate_v2.flag');
            if (!file_exists($freshFlag)) {
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
                    'contact_messages',
                    'data_requests',
                    'otp_codes',
                    'password_reset_tokens',
                    'activity_logs',
                    'notification_logs',
                    'jobs',
                    'failed_jobs',
                    'job_batches',
                ];
                foreach ($truncateTables as $table) {
                    if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                        \Illuminate\Support\Facades\DB::table($table)->truncate();
                    }
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    \Illuminate\Support\Facades\DB::table('users')->where('role', 'customer')->delete();

                    // Ensure admin account exists, is active, and has correct admin role
                    $admin = \Illuminate\Support\Facades\DB::table('users')->where('role', 'admin')->first();
                    if (!$admin) {
                        \Illuminate\Support\Facades\DB::table('users')->insert([
                            'name' => 'Store Administrator',
                            'email' => 'nabeel@store.com',
                            'phone' => '+60128899900',
                            'role' => 'admin',
                            'is_active' => 1,
                            'email_verified_at' => now(),
                            'password' => \Illuminate\Support\Facades\Hash::make('Admin@123456'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        \Illuminate\Support\Facades\DB::table('users')
                            ->where('role', 'admin')
                            ->update(['is_active' => 1]);
                    }
                }
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

                // Clean uploaded catalog storage folders
                try {
                    \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('products');
                    \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('categories');
                    \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('brands');
                } catch (\Throwable $e) {}

                @file_put_contents($freshFlag, date('Y-m-d H:i:s'));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Live DB fresh reset notice: ' . $e->getMessage());
        }

        // Synchronize uploaded files from storage/app/public into public/storage for InfinityFree shared hosting
        try {
            $syncDirs = ['theme', 'products', 'categories', 'brands', 'banners'];
            foreach ($syncDirs as $d) {
                $srcDir = storage_path('app/public/' . $d);
                $dstDir = public_path('storage/' . $d);
                if (is_dir($srcDir)) {
                    if (!is_dir($dstDir)) {
                        @mkdir($dstDir, 0755, true);
                    }
                    $items = @scandir($srcDir);
                    if ($items) {
                        foreach ($items as $item) {
                            if ($item !== '.' && $item !== '..') {
                                $srcFile = $srcDir . DIRECTORY_SEPARATOR . $item;
                                $dstFile = $dstDir . DIRECTORY_SEPARATOR . $item;
                                if (is_file($srcFile) && !file_exists($dstFile)) {
                                    @copy($srcFile, $dstFile);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}
    }
}
