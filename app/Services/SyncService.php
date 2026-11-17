<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SyncQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncService
{
    protected string $liveUrl;
    protected string $apiToken;
    protected int $timeout;
    protected int $batchSize;

    public function __construct()
    {
        $this->liveUrl = rtrim(config('sync.live_url', 'https://nabeel.site.je'), '/');
        $this->apiToken = config('sync.api_token', 'pos_sync_secret_key_nabeel_2026');
        $this->timeout = (int) config('sync.timeout_seconds', 15);
        $this->batchSize = (int) config('sync.batch_size', 50);
    }

    /**
     * Build prepared HTTP client instance.
     */
    protected function client()
    {
        $client = Http::timeout($this->timeout)
            ->withHeaders([
                'X-Sync-Token' => $this->apiToken,
                'Accept' => 'application/json',
            ]);

        if (!config('sync.verify_ssl', false)) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    /**
     * Check if live remote server is accessible and responding.
     */
    public function pingLive(): array
    {
        try {
            $response = $this->client()->get("{$this->liveUrl}/api/sync/ping");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'online' => true,
                    'server_time' => $data['server_time'] ?? now()->toISOString(),
                    'message' => 'Connected to live cloud server.',
                ];
            }

            return [
                'online' => false,
                'status_code' => $response->status(),
                'message' => 'Live server returned error: HTTP ' . $response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'online' => false,
                'message' => 'Offline / Host unreachable: ' . Str::limit($e->getMessage(), 120),
            ];
        }
    }

    /**
     * Push all pending offline queue records to live server.
     */
    public function pushToLive(): array
    {
        $pendingItems = SyncQueue::pending()
            ->limit($this->batchSize)
            ->get();

        if ($pendingItems->isEmpty()) {
            return [
                'success' => true,
                'pushed_count' => 0,
                'message' => 'Queue is empty. Everything is up to date.',
            ];
        }

        // Group records by type
        $orders = [];
        $products = [];
        $expenses = [];

        foreach ($pendingItems as $item) {
            if ($item->record_type === 'order') {
                $orders[] = $item->payload;
            } elseif ($item->record_type === 'product') {
                $products[] = $item->payload;
            } elseif ($item->record_type === 'product_expense') {
                $expenses[] = $item->payload;
            }
        }

        try {
            $response = $this->client()
                ->post("{$this->liveUrl}/api/sync/push", [
                    'orders' => $orders,
                    'products' => $products,
                    'expenses' => $expenses,
                    'pushed_at' => now()->toISOString(),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $syncedUuids = $data['synced_uuids'] ?? [];

                // Mark processed items as synced
                foreach ($pendingItems as $item) {
                    if (empty($syncedUuids) || in_array($item->sync_uuid, $syncedUuids, true)) {
                        $item->markSynced();
                    }
                }

                $this->updateLastSyncTimestamp('push');

                return [
                    'success' => true,
                    'pushed_count' => $pendingItems->count(),
                    'message' => "Successfully synchronized {$pendingItems->count()} records to live server.",
                ];
            }

            $errorMsg = 'Live push failed with HTTP ' . $response->status() . ': ' . Str::limit($response->body(), 300);
            foreach ($pendingItems as $item) {
                $item->markFailed($errorMsg);
            }

            return [
                'success' => false,
                'pushed_count' => 0,
                'message' => $errorMsg,
            ];
        } catch (\Throwable $e) {
            $errorMsg = 'Network error during sync: ' . Str::limit($e->getMessage(), 300);
            foreach ($pendingItems as $item) {
                $item->markFailed($errorMsg);
            }

            return [
                'success' => false,
                'pushed_count' => 0,
                'message' => $errorMsg,
            ];
        }
    }

    /**
     * Pull catalog updates, new products, and stock updates from live server into local DB.
     */
    public function pullFromLive(): array
    {
        $lastSync = Setting::where('key', 'last_live_catalog_sync_at')->value('value');

        try {
            $response = $this->client()->get("{$this->liveUrl}/api/sync/pull", [
                'since' => $lastSync,
            ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'pulled_count' => 0,
                    'message' => 'Pull failed: HTTP ' . $response->status(),
                ];
            }

            $data = $response->json();
            $categories = $data['categories'] ?? [];
            $brands = $data['brands'] ?? [];
            $products = $data['products'] ?? [];
            $serverTime = $data['server_time'] ?? now()->toISOString();

            // 1. Sync Categories
            foreach ($categories as $catData) {
                Category::updateOrCreate(
                    ['slug' => $catData['slug']],
                    [
                        'name' => $catData['name'],
                        'is_active' => $catData['is_active'] ?? true,
                        'sort_order' => $catData['sort_order'] ?? 0,
                    ]
                );
            }

            // 2. Sync Brands
            foreach ($brands as $brandData) {
                if (!empty($brandData['name'])) {
                    Brand::updateOrCreate(
                        ['name' => $brandData['name']],
                        [
                            'slug' => $brandData['slug'] ?? Str::slug($brandData['name']),
                            'is_active' => $brandData['is_active'] ?? true,
                        ]
                    );
                }
            }

            // 3. Sync Products
            $updatedProductsCount = 0;
            foreach ($products as $prodData) {
                if (empty($prodData['sku'])) {
                    continue;
                }

                // Match category
                $categoryId = 1;
                if (!empty($prodData['category_slug'])) {
                    $cat = Category::where('slug', $prodData['category_slug'])->first();
                    if ($cat) {
                        $categoryId = $cat->id;
                    }
                }

                // Match brand
                $brandId = null;
                if (!empty($prodData['brand_name'])) {
                    $brand = Brand::where('name', $prodData['brand_name'])->first();
                    if ($brand) {
                        $brandId = $brand->id;
                    }
                }

                Product::updateOrCreate(
                    ['sku' => $prodData['sku']],
                    [
                        'name' => $prodData['name'],
                        'slug' => $prodData['slug'] ?? Str::slug($prodData['name']),
                        'category_id' => $categoryId,
                        'brand_id' => $brandId,
                        'price' => (float) $prodData['price'],
                        'sale_price' => !empty($prodData['sale_price']) ? (float) $prodData['sale_price'] : null,
                        'purchase_price' => (float) ($prodData['purchase_price'] ?? 0),
                        'stock_quantity' => (int) $prodData['stock_quantity'],
                        'pack_size' => $prodData['pack_size'] ?? '',
                        'unit_description' => $prodData['unit_description'] ?? '',
                        'weight_volume' => $prodData['weight_volume'] ?? '',
                        'is_active' => (bool) ($prodData['is_active'] ?? true),
                        'sync_uuid' => $prodData['sync_uuid'] ?? null,
                        'sync_status' => 'synced',
                    ]
                );
                $updatedProductsCount++;
            }

            $this->updateLastSyncTimestamp('pull', $serverTime);

            return [
                'success' => true,
                'pulled_count' => $updatedProductsCount,
                'categories_count' => count($categories),
                'message' => "Downloaded {$updatedProductsCount} product updates from live server.",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'pulled_count' => 0,
                'message' => 'Pull error: ' . Str::limit($e->getMessage(), 200),
            ];
        }
    }

    /**
     * Execute full bidirectional synchronization (Push offline changes -> Pull latest catalog).
     */
    public function syncAll(): array
    {
        $ping = $this->pingLive();
        if (!$ping['online']) {
            return [
                'success' => false,
                'is_online' => false,
                'pushed_count' => 0,
                'pulled_count' => 0,
                'message' => $ping['message'],
            ];
        }

        $pushResult = $this->pushToLive();
        $pullResult = $this->pullFromLive();

        $overallSuccess = $pushResult['success'] && $pullResult['success'];
        $message = "Sync Completed. Pushed: {$pushResult['pushed_count']} local record(s). Updated: {$pullResult['pulled_count']} product(s).";

        return [
            'success' => $overallSuccess,
            'is_online' => true,
            'pushed_count' => $pushResult['pushed_count'],
            'pulled_count' => $pullResult['pulled_count'],
            'message' => $message,
            'last_synced_at' => now()->format('d M Y, h:i A'),
        ];
    }

    /**
     * Get current status overview for UI display.
     */
    public function getStatus(): array
    {
        SyncQueue::ensureTableExists();

        $pendingCount = SyncQueue::pending()->count();
        $lastSync = Setting::where('key', 'last_live_sync_at')->value('value');

        return [
            'pending_count' => $pendingCount,
            'last_synced_at' => $lastSync ?: 'Not yet synced',
            'live_url' => $this->liveUrl,
        ];
    }

    /**
     * Store timestamp of last successful sync.
     */
    protected function updateLastSyncTimestamp(string $action, ?string $serverTime = null): void
    {
        try {
            $formattedTime = now()->format('Y-m-d H:i:s');
            Setting::updateOrInsert(
                ['key' => 'last_live_sync_at'],
                ['value' => $formattedTime, 'group' => 'sync', 'created_at' => now(), 'updated_at' => now()]
            );

            if ($action === 'pull' && $serverTime) {
                Setting::updateOrInsert(
                    ['key' => 'last_live_catalog_sync_at'],
                    ['value' => $serverTime, 'group' => 'sync', 'created_at' => now(), 'updated_at' => now()]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Could not record last sync timestamp: ' . $e->getMessage());
        }
    }
}
