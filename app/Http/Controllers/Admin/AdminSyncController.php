<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyncQueue;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSyncController extends Controller
{
    /**
     * Get live sync state for POS and admin topbar status widgets.
     */
    public function status(SyncService $syncService): JsonResponse
    {
        $status = $syncService->getStatus();
        $ping = $syncService->pingLive();

        return response()->json([
            'success' => true,
            'is_online' => $ping['online'],
            'ping_message' => $ping['message'],
            'pending_count' => $status['pending_count'],
            'last_synced_at' => $status['last_synced_at'],
            'live_url' => $status['live_url'],
        ]);
    }

    /**
     * Trigger manual or automated synchronization round.
     */
    public function triggerSync(SyncService $syncService): JsonResponse
    {
        $result = $syncService->syncAll();

        return response()->json($result);
    }

    /**
     * Display the full Sync Center management screen.
     */
    public function index(SyncService $syncService): View
    {
        SyncQueue::ensureTableExists();

        $status = $syncService->getStatus();
        $ping = $syncService->pingLive();

        $pendingItems = SyncQueue::orderBy('id', 'desc')->paginate(20);

        return view('admin.sync.index', compact('status', 'ping', 'pendingItems'));
    }

    /**
     * Retry a failed sync queue item.
     */
    public function retry(SyncQueue $syncQueue): JsonResponse
    {
        $syncQueue->update([
            'status' => 'pending',
            'attempts' => 0,
            'error_message' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Record marked for immediate retry.',
        ]);
    }

    /**
     * Clear all synced queue items.
     */
    public function clearSynced(): JsonResponse
    {
        SyncQueue::where('status', 'synced')->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cleaned up completed sync logs.',
        ]);
    }
}
