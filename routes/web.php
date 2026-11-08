<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Technical SEO Routes (Phase 5.4)
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Storefront Customer Routes (Phase 1 & Phase 3)
|--------------------------------------------------------------------------
*/
// Media Storage Asset Handler & Fallback (Shared Hosting Support)
Route::get('/storage/{path}', function ($path) {
    $storageFile = storage_path('app/public/' . $path);
    if (file_exists($storageFile)) {
        return response()->file($storageFile);
    }
    $publicFile = public_path('storage/' . $path);
    if (file_exists($publicFile)) {
        return response()->file($publicFile);
    }
    return response()->file(public_path('images/default-product.png'));
})->where('path', '.*');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Search (with rate limiting)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/search/live', [ProductController::class, 'liveSearch'])->name('product.live-search');
});

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Products
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Shopping Cart (Session-based)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/summary', [CartController::class, 'summary'])->name('summary');
});

// Checkout & Delivery Zone Calculation (Phase 3)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/evaluate-zone', [CheckoutController::class, 'evaluateZone'])->name('evaluate-zone');
    Route::post('/store', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('store');
    Route::get('/success/{order_number}', [CheckoutController::class, 'success'])->name('success');
});

// Order Tracking
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('order.track');
Route::get('/order/scan/{orderNumber}', function ($orderNumber) {
    if (auth()->check() && in_array(auth()->user()->role, ['admin', 'staff'])) {
        return redirect()->route('admin.orders.barcode', $orderNumber);
    }
    return redirect()->route('checkout.success', $orderNumber);
})->name('order.scan');

// CMS, Legal & Policy Pages (Phase 8)
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('page.about');
Route::get('/contact', [PageController::class, 'contact'])->name('page.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->middleware('throttle:5,1')->name('page.contact.submit');
Route::get('/faqs', [PageController::class, 'faqs'])->name('page.faqs');
Route::get('/data-request', [PageController::class, 'dataRequest'])->name('page.data-request');
Route::post('/data-request', [PageController::class, 'submitDataRequest'])->middleware('throttle:5,1')->name('page.data-request.submit');

// Customer Account Protected Routes
Route::middleware(['auth', 'customer.verified'])->prefix('account')->name('account.')->group(function () {
    Route::get('/orders', [CustomerAccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order_number}', [CustomerAccountController::class, 'orderDetails'])->name('orders.details');
    Route::get('/addresses', [CustomerAccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [CustomerAccountController::class, 'storeAddress'])->name('addresses.store');
    Route::delete('/addresses/{id}', [CustomerAccountController::class, 'deleteAddress'])->name('addresses.delete');
    Route::get('/export-data', [CustomerAccountController::class, 'exportData'])->name('export-data');
});

// Profile Management & Generic Dashboard Redirect
Route::middleware(['auth', 'customer.verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'staff')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('account.orders');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dynamic Legal Policy Slugs (Must be placed after static routes)
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', 'privacy-policy|terms-and-conditions|refund-and-return-policy|shipping-and-delivery-policy|payment-policy|cancellation-policy|cookie-policy|security-policy|disclaimer')
    ->name('page.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
