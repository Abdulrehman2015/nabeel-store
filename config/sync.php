<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Live Server URL
    |--------------------------------------------------------------------------
    | The base URL of your remote live server (e.g. https://nabeel.site.je)
    | where offline sales and products will be synced when internet is connected.
    */
    'live_url' => env('SYNC_LIVE_URL', 'https://nabeel.site.je'),

    /*
    |--------------------------------------------------------------------------
    | Sync Security API Token
    |--------------------------------------------------------------------------
    | A shared secret key between this local desktop app and the live server.
    | Requests to /api/sync/push and /api/sync/pull must supply this token.
    */
    'api_token' => env('SYNC_API_TOKEN', 'pos_sync_secret_key_nabeel_2026'),

    /*
    |--------------------------------------------------------------------------
    | Mode Configuration
    |--------------------------------------------------------------------------
    | 'node' = offline desktop register pushing to live
    | 'master' = cloud server receiving data
    */
    'is_offline_node' => env('IS_OFFLINE_NODE', false),

    /*
    |--------------------------------------------------------------------------
    | Sync Batch & Connection Limits
    |--------------------------------------------------------------------------
    */
    'batch_size' => (int) env('SYNC_BATCH_SIZE', 50),
    'timeout_seconds' => (int) env('SYNC_TIMEOUT_SECONDS', 15),
    'verify_ssl' => (bool) env('SYNC_VERIFY_SSL', false),
];
