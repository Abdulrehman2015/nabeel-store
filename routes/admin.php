<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPolicyController;
use App\Http\Controllers\Admin\AdminPosController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProductExpenseController;
use App\Http\Controllers\Admin\AdminProductImportExportController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminStaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Phase 2)
| Protected by 'auth' and 'admin' middleware
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Notifications
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications/poll', [AdminDashboardController::class, 'pollNotifications'])->name('notifications.poll');

    // Daily Notifications & Alert Feed Module (30-day auto retention)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
        Route::post('/purge-old', [AdminNotificationController::class, 'purgeOld'])->name('purge-old');
        Route::post('/clear-all', [AdminNotificationController::class, 'clearAll'])->name('clear-all');
    });

    // Internal Product Consumption & Staff Expense Module
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [AdminProductExpenseController::class, 'index'])->name('index');
        Route::post('/', [AdminProductExpenseController::class, 'store'])->name('store');
        Route::delete('/{expense}', [AdminProductExpenseController::class, 'destroy'])->name('destroy');
        Route::get('/export', [AdminProductExpenseController::class, 'export'])->name('export');
    });

    // One-click Database Migration Runner for Shared Hosting
    Route::get('/migrate-db', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            return response()->json([
                'success' => true,
                'message' => 'Migrations executed successfully!',
                'output' => $output,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    })->name('migrate-db');

    // Point of Sale (POS) Cashier Register
    Route::get('pos', [AdminPosController::class, 'index'])->name('pos.index');
    Route::post('pos/checkout', [AdminPosController::class, 'checkout'])->name('pos.checkout');
    Route::post('pos/void-order', [AdminPosController::class, 'voidOrder'])->name('pos.void-order');
    Route::get('pos/receipt-test', [AdminPosController::class, 'testReceipt'])->name('pos.receipt.test');
    Route::get('pos/receipt/{order}', [AdminPosController::class, 'receipt'])->name('pos.receipt');
    Route::post('pos/customer', [AdminPosController::class, 'quickCustomer'])->name('pos.customer');
    Route::post('pos/quick-product', [AdminPosController::class, 'quickProduct'])->name('pos.quick-product');
    Route::get('pos/shift-summary', [AdminPosController::class, 'shiftSummary'])->name('pos.shift-summary');

    // Categories (Cashier can view/create, but cannot bulk delete)
    Route::delete('categories/delete-all', [AdminCategoryController::class, 'deleteAll'])->name('categories.delete-all')->middleware('admin.only');
    Route::resource('categories', AdminCategoryController::class);

    // Brands (Cashier can view/create, but cannot bulk delete)
    Route::delete('brands/delete-all', [AdminBrandController::class, 'deleteAll'])->name('brands.delete-all')->middleware('admin.only');
    Route::resource('brands', AdminBrandController::class);

    // Products & Bulk Import/Export (Cashier can view and add, but cannot delete)
    Route::post('products/seed-dummy', [AdminProductController::class, 'seedDummy'])->name('products.seed-dummy');
    Route::delete('products/delete-all', [AdminProductController::class, 'deleteAll'])->name('products.delete-all')->middleware('admin.only');
    Route::get('products/export', [AdminProductImportExportController::class, 'export'])->name('products.export');
    Route::post('products/import', [AdminProductImportExportController::class, 'import'])->name('products.import')->middleware('admin.only');

    // Route aliases and fallbacks for product pages
    Route::get('product', fn() => redirect()->route('admin.products.index'));
    Route::get('product/create', fn() => redirect()->route('admin.products.create'));
    Route::get('products/add', fn() => redirect()->route('admin.products.create'));
    Route::get('product/add', fn() => redirect()->route('admin.products.create'));
    Route::get('add-product', fn() => redirect()->route('admin.products.create'));
    Route::get('create-product', fn() => redirect()->route('admin.products.create'));

    Route::resource('products', AdminProductController::class);

    // Orders Management & PDF Invoices
    Route::get('orders/barcode/{orderNumber}', function ($orderNumber) {
        $clean = trim($orderNumber);
        $order = \App\Models\Order::where('order_number', $clean)
            ->orWhere('order_number', 'LIKE', "%{$clean}%")
            ->first();

        if ($order) {
            return redirect()->route('admin.orders.show', $order);
        }

        return redirect()->route('admin.orders.index', ['search' => $clean]);
    })->name('orders.barcode');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::post('orders/{order}/reject-payment', [AdminOrderController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/internal-note', [AdminOrderController::class, 'addInternalNote'])->name('orders.internal-note');
    Route::get('orders/{order}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::get('orders/{order}/payment-proof', [AdminOrderController::class, 'viewPaymentProof'])->name('orders.payment-proof');

    // Customers (Cashiers can view list & customer details, but cannot delete)
    Route::delete('customers/delete-all', [AdminCustomerController::class, 'deleteAll'])->name('customers.delete-all')->middleware('admin.only');
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::post('customers/{user}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('customers/{user}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy')->middleware('admin.only');

    // --------------------------------------------------------------------------
    // Administrative Operations Strictly Restricted to Admins (Hidden from Cashiers)
    // --------------------------------------------------------------------------
    Route::middleware('admin.only')->group(function () {
        // Staff & Cashier User Management
        Route::post('staff/{staff}/toggle-status', [AdminStaffController::class, 'toggleStatus'])->name('staff.toggle-status');
        Route::resource('staff', AdminStaffController::class);

        // Sales & Product Performance Reports Module
        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [AdminReportController::class, 'export'])->name('reports.export');

        // System Data Cleanup / Store Reset Tool
        Route::get('data-reset', [AdminSettingsController::class, 'dataReset'])->name('data-reset');
        Route::post('data-reset', [AdminSettingsController::class, 'processDataReset'])->name('data-reset.process');
        Route::post('data-reset/clean-slate', [AdminSettingsController::class, 'cleanSlate'])->name('data-reset.clean-slate');

        // Banners / Slider
        Route::resource('banners', AdminBannerController::class);

        // Dynamic CMS Modules
        Route::get('home-sections', [AdminCmsController::class, 'homeSections'])->name('home-sections.index');
        Route::post('home-sections/reorder', [AdminCmsController::class, 'reorderHomeSections'])->name('home-sections.reorder');
        Route::resource('how-it-works', AdminCmsController::class)->names('how-it-works');
        Route::resource('benefits', AdminCmsController::class)->names('benefits');
        Route::resource('faqs', AdminCmsController::class)->names('faqs');
        Route::get('contact-messages', [AdminCmsController::class, 'contactMessages'])->name('contact-messages.index');
        Route::post('contact-messages/{message}/mark-read', [AdminCmsController::class, 'markMessageRead'])->name('contact-messages.mark-read');
        Route::get('data-requests', [AdminCmsController::class, 'dataRequests'])->name('data-requests.index');
        Route::post('data-requests/{request}/process', [AdminCmsController::class, 'processDataRequest'])->name('data-requests.process');

        // Legal & Policy Pages (Phase 8 with Version History)
        Route::resource('policies', AdminPolicyController::class);
        Route::post('policies/{policy}/restore/{version}', [AdminPolicyController::class, 'restoreVersion'])->name('policies.restore');

        // Settings (Delivery, Payment, Notification, Appearance, Master SEO, Page SEO)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/delivery', [AdminSettingsController::class, 'delivery'])->name('delivery');
            Route::post('/delivery', [AdminSettingsController::class, 'saveDelivery'])->name('delivery.save');
            Route::post('/delivery/resolve-link', [AdminSettingsController::class, 'resolveMapLink'])->name('delivery.resolve-link');

            Route::get('/payment', [AdminSettingsController::class, 'payment'])->name('payment');
            Route::post('/payment', [AdminSettingsController::class, 'savePayment'])->name('payment.save');

            Route::get('/notifications', [AdminSettingsController::class, 'notifications'])->name('notifications');
            Route::post('/notifications', [AdminSettingsController::class, 'saveNotifications'])->name('notifications.save');

            Route::get('/appearance', [AdminSettingsController::class, 'appearance'])->name('appearance');
            Route::post('/appearance', [AdminSettingsController::class, 'saveAppearance'])->name('appearance.save');

            Route::get('/homepage', [AdminSettingsController::class, 'homepage'])->name('homepage');
            Route::post('/homepage', [AdminSettingsController::class, 'saveHomepage'])->name('homepage.save');
            Route::delete('/homepage/banner/{banner}', [AdminSettingsController::class, 'deleteBanner'])->name('homepage.banner.delete');

            Route::get('/master-seo', [AdminSettingsController::class, 'masterSeo'])->name('master-seo');
            Route::post('/master-seo', [AdminSettingsController::class, 'saveMasterSeo'])->name('master-seo.save');

            Route::get('/page-seo', [AdminSettingsController::class, 'pageSeo'])->name('page-seo');
            Route::post('/page-seo', [AdminSettingsController::class, 'savePageSeo'])->name('page-seo.save');
        });

        // Activity & Audit Log (Phase 7.1)
        Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // Profile & Password Settings (Accessible to both Admin & Staff/Cashier)
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
});

