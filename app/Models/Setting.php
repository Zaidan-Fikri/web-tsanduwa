<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'site_settings';

    /**
     * Semua pengaturan dalam bentuk [key => value], di-cache agar tidak query tiap request.
     */
    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function getValue(string $key, ?string $default = null): ?string
    {
        $value = static::allCached()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
