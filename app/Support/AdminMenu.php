<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Daftar menu panel admin (urutan mengikuti navbar website).
 *
 * Dipakai bersama oleh: sidebar, pilihan hak akses di form Pengguna, dan middleware
 * yang menolak akses ke menu yang tidak diizinkan. Tiap item:
 *  - key      : kode hak akses yang disimpan di users.permissions
 *  - route    : halaman tujuan di sidebar (atau 'settings' => grup pengaturan)
 *  - match    : nama route yang termasuk menu ini (untuk menu aktif & pengecekan akses)
 */
class AdminMenu
{
    public static function groups(): array
    {
        return [
            ['label' => 'Beranda', 'icon' => 'fa-house', 'items' => [
                ['key' => 'slider', 'label' => 'Slider Utama', 'icon' => 'fa-images', 'route' => 'admin.slider.index', 'match' => ['admin.slider.*']],
                ['key' => 'agenda', 'label' => 'Agenda', 'icon' => 'fa-calendar-days', 'route' => 'admin.agenda.index', 'match' => ['admin.agenda.*']],
                ['key' => 'fasilitas', 'label' => 'Fasilitas', 'icon' => 'fa-building', 'route' => 'admin.fasilitas.index', 'match' => ['admin.fasilitas.*']],
                ['key' => 'beranda', 'label' => 'Statistik & Video', 'icon' => 'fa-chart-simple', 'settings' => 'beranda'],
            ]],
            ['label' => 'Profil', 'icon' => 'fa-id-card', 'items' => [
                ['key' => 'sambutan', 'label' => 'Sambutan Kepala', 'icon' => 'fa-user-tie', 'settings' => 'sambutan'],
                ['key' => 'identitas', 'label' => 'Identitas Sekolah', 'icon' => 'fa-id-card', 'settings' => 'identitas'],
                ['key' => 'visimisi', 'label' => 'Visi & Misi', 'icon' => 'fa-bullseye', 'route' => 'admin.misi.index', 'match' => ['admin.misi.*'], 'settings' => 'visi'],
                ['key' => 'sejarah', 'label' => 'Sejarah', 'icon' => 'fa-landmark', 'settings' => 'sejarah'],
                ['key' => 'struktur', 'label' => 'Struktur Organisasi', 'icon' => 'fa-sitemap', 'route' => 'admin.struktur.index', 'match' => ['admin.struktur.*']],
                ['key' => 'unit-kerja', 'label' => 'Unit Kerja', 'icon' => 'fa-briefcase', 'route' => 'admin.unit-kerja.index', 'match' => ['admin.unit-kerja.*']],
                ['key' => 'sdm', 'label' => 'Guru & Tendik', 'icon' => 'fa-chalkboard-user', 'route' => 'admin.sdm.index', 'match' => ['admin.sdm.*']],
            ]],
            ['label' => 'Program Madrasah', 'icon' => 'fa-book-quran', 'items' => [
                ['key' => 'program', 'label' => 'Program Madrasah', 'icon' => 'fa-book-quran', 'route' => 'admin.program.index', 'match' => ['admin.program.*']],
            ]],
            ['label' => 'Ekstrakurikuler', 'icon' => 'fa-person-running', 'items' => [
                ['key' => 'ekskul', 'label' => 'Ekstrakurikuler', 'icon' => 'fa-person-running', 'route' => 'admin.ekskul.index', 'match' => ['admin.ekskul.*']],
            ]],
            ['label' => 'Informasi', 'icon' => 'fa-newspaper', 'items' => [
                ['key' => 'berita', 'label' => 'Berita', 'icon' => 'fa-newspaper', 'route' => 'admin.berita.index', 'match' => ['admin.berita.*']],
                ['key' => 'prestasi', 'label' => 'Prestasi', 'icon' => 'fa-trophy', 'route' => 'admin.prestasi.index', 'match' => ['admin.prestasi.*']],
                ['key' => 'galeri', 'label' => 'Galeri', 'icon' => 'fa-photo-film', 'route' => 'admin.galeri.index', 'match' => ['admin.galeri.*']],
            ]],
            ['label' => 'SPMB', 'icon' => 'fa-user-plus', 'items' => [
                ['key' => 'spmb', 'label' => 'Info SPMB & Brosur', 'icon' => 'fa-bullhorn', 'settings' => 'spmb'],
                ['key' => 'spmb-prosedur', 'label' => 'Prosedur Pendaftaran', 'icon' => 'fa-list-ol', 'route' => 'admin.spmb-prosedur.index', 'match' => ['admin.spmb-prosedur.*']],
                ['key' => 'spmb-persyaratan', 'label' => 'Persyaratan', 'icon' => 'fa-folder-open', 'route' => 'admin.spmb-persyaratan.index', 'match' => ['admin.spmb-persyaratan.*']],
            ]],
            ['label' => 'Pembayaran', 'icon' => 'fa-wallet', 'items' => [
                ['key' => 'spp', 'label' => 'Tagihan Pembayaran Sekolah', 'icon' => 'fa-file-invoice-dollar', 'route' => 'admin.spp.index',
                    'match' => ['admin.spp.index', 'admin.spp.edit', 'admin.spp.pay', 'admin.spp.update', 'admin.spp.payment.cancel', 'admin.spp.destroy', 'admin.spp.destroy-class', 'admin.spp.codes', 'admin.spp.reset-code']],
                ['key' => 'spp-import', 'label' => 'Upload Excel Tagihan', 'icon' => 'fa-file-excel', 'route' => 'admin.spp.import',
                    'match' => ['admin.spp.import', 'admin.spp.upload', 'admin.spp.preview', 'admin.spp.commit', 'admin.spp.cancel']],
                ['key' => 'pembayaran', 'label' => 'Info Pembayaran', 'icon' => 'fa-money-bill-transfer', 'settings' => 'pembayaran'],
            ]],
            ['label' => 'Pengaturan', 'icon' => 'fa-gear', 'items' => [
                ['key' => 'kontak', 'label' => 'Kontak & Media Sosial', 'icon' => 'fa-address-book', 'settings' => 'kontak'],
            ]],
        ];
    }

    /** @return array<string, array> [key => item] */
    public static function items(): array
    {
        $items = [];
        foreach (static::groups() as $group) {
            foreach ($group['items'] as $item) {
                $items[$item['key']] = $item + ['group' => $group['label']];
            }
        }

        return $items;
    }

    public static function keys(): array
    {
        return array_keys(static::items());
    }

    public static function url(array $item): string
    {
        return isset($item['route']) ? route($item['route']) : route('admin.settings.edit', $item['settings']);
    }

    /**
     * Menu mana yang sedang dibuka (null = halaman umum: dashboard, akun, logout).
     */
    public static function keyForRequest(Request $request): ?string
    {
        $route = $request->route();
        $settingsGroup = $request->routeIs('admin.settings.*') ? $route?->parameter('group') : null;

        foreach (static::items() as $key => $item) {
            if ($settingsGroup !== null && ($item['settings'] ?? null) === $settingsGroup) {
                return $key;
            }
            if (! empty($item['match']) && $request->routeIs(...$item['match'])) {
                return $key;
            }
        }

        return null;
    }
}
