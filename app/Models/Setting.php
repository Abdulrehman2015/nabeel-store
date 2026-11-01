<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public const CACHE_KEY = 'app_settings_cache';

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Get all settings as key => value array (cached).
     */
    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                return self::query()->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    /**
     * Retrieve a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::allCached();
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    /**
     * Set a single setting value.
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text'): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value, 'group' => $group, 'type' => $type]
        );

        Cache::forget(self::CACHE_KEY);
        return $setting;
    }

    /**
     * Set multiple settings at once.
     */
    public static function setMany(array $keyValues, string $group = 'general'): void
    {
        foreach ($keyValues as $key => $value) {
            self::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => $group]
            );
        }

        Cache::forget(self::CACHE_KEY);
    }
}
