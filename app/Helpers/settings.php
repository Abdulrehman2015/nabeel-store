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

        return Setting::get($key, $default);
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

