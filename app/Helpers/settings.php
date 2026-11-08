<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get or set setting value from cached key-value store.
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(Setting::class);
        }

        if (is_array($key)) {
            return Setting::setMany($key);
        }

        if ($key === 'site_name' || $key === 'store_name') {
            $val = Setting::get('store_name') ?: Setting::get('site_name');
            return $val ?: $default;
        }

        return Setting::get($key, $default);
    }
}

if (!function_exists('store_name')) {
    /**
     * Get the dynamic store name across the entire application.
     *
     * @param string|null $default
     * @return string
     */
    function store_name($default = 'N&J Mini Mart')
    {
        $name = setting('store_name') ?: setting('site_name');
        return $name ?: config('app.name', $default);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format money with store currency symbol and decimals.
     *
     * @param float|int|string|null $amount
     * @return string
     */
    function format_currency($amount)
    {
        $symbol = setting('currency_symbol', 'RM');
        $decimals = (int) setting('currency_decimals', 2);
        $amount = (float) ($amount ?? 0);
        return $symbol . ' ' . number_format($amount, $decimals);
    }
}

