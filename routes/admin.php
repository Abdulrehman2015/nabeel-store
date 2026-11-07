<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPolicyController;
use App\Http\Controllers\Admin\AdminPosController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProductImportExportController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Phase 2)
| Protected by 'auth' and 'admin' middleware
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications/poll', [AdminDashboardController::class, 'pollNotifications'])->name('notifications.poll');

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
    Route::get('pos/receipt-test', [AdminPosController::class, 'testReceipt'])->name('pos.receipt.test');
    Route::get('pos/receipt/{order}', [AdminPosController::class, 'receipt'])->name('pos.receipt');
    Route::post('pos/customer', [AdminPosController::class, 'quickCustomer'])->name('pos.customer');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Brands
    Route::resource('brands', AdminBrandController::class);

    // Products & Bulk Import/Export
    Route::get('products/export', [AdminProductImportExportController::class, 'export'])->name('products.export');
    Route::post('products/import', [AdminProductImportExportController::class, 'import'])->name('products.import');
    Route::resource('products', AdminProductController::class);

    // Orders Management & PDF Invoices
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::post('orders/{order}/reject-payment', [AdminOrderController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/internal-note', [AdminOrderController::class, 'addInternalNote'])->name('orders.internal-note');
    Route::get('orders/{order}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::get('orders/{order}/payment-proof', [AdminOrderController::class, 'viewPaymentProof'])->name('orders.payment-proof');

    // Sales & Product Performance Reports Module
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [AdminReportController::class, 'export'])->name('reports.export');

    // Customers
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::post('customers/{user}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('customers/{user}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');

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

    // Admin Profile & Security Settings
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Activity & Audit Log (Phase 7.1)
    Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
});

