<?php

use App\Models\Setting;
use Illuminate\Support\HtmlString;

if (! function_exists('setting')) {
    /**
     * Ambil nilai pengaturan situs (lihat App\Support\SettingGroups untuk daftar key).
     */
    function setting(string $key, ?string $default = null): ?string
    {
        return Setting::getValue($key, $default);
    }
}

if (! function_exists('media_url')) {
    /**
     * URL gambar: path lokal (img/..., uploads/...) atau URL https penuh.
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback ? media_url($fallback) : null;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}

if (! function_exists('youtube_id')) {
    /**
     * Ambil ID video dari berbagai format URL YouTube (watch, youtu.be, embed, shorts).
     */
    function youtube_id(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $pattern = '~(?:youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~i';

        return preg_match($pattern, $url, $m) ? $m[1] : null;
    }
}

if (! function_exists('paragraphs')) {
    /**
     * Ubah teks biasa menjadi paragraf HTML. Teks di-escape lebih dulu sehingga
     * aman dari XSS walaupun diisi lewat panel admin.
     */
    function paragraphs(?string $text, string $class = ''): HtmlString
    {
        $blocks = preg_split('/\R{2,}/', trim((string) $text)) ?: [];
        $attr = $class !== '' ? ' class="'.e($class).'"' : '';

        $html = collect($blocks)
            ->filter(fn ($block) => trim($block) !== '')
            ->map(fn ($block) => '<p'.$attr.'>'.nl2br(e(trim($block)), false).'</p>')
            ->implode("\n");

        return new HtmlString($html);
    }
}

if (! function_exists('wa_link')) {
    /**
     * Link wa.me dari nomor lokal (0819...) beserta pesan opsional.
     */
    function wa_link(?string $number, ?string $message = null): string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits.($message ? '?text='.rawurlencode($message) : '');
    }
}

if (! function_exists('tel_link')) {
    function tel_link(?string $number): string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'tel:+'.$digits;
    }
}

if (! function_exists('admin_path')) {
    function admin_path(): string
    {
        return trim(config('app.admin_path', 'admin'), '/') ?: 'admin';
    }
}

if (! function_exists('rupiah')) {
    /** 160000 => "Rp 160.000" */
    function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
