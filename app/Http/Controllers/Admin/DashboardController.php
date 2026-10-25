<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Staff;
use App\Models\SppBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Hanya tampilkan kartu & aksi untuk menu yang boleh diakses pengguna ini.
        $stats = collect([
            ['berita', 'Berita', fn () => Post::count(), 'fa-newspaper', 'admin.berita.index'],
            ['prestasi', 'Prestasi', fn () => Achievement::count(), 'fa-trophy', 'admin.prestasi.index'],
            ['sdm', 'Guru & Tendik', fn () => Staff::count(), 'fa-chalkboard-user', 'admin.sdm.index'],
            ['ekskul', 'Ekstrakurikuler', fn () => Extracurricular::count(), 'fa-person-running', 'admin.ekskul.index'],
            ['galeri', 'Galeri', fn () => Gallery::count(), 'fa-images', 'admin.galeri.index'],
            ['agenda', 'Agenda', fn () => Agenda::count(), 'fa-calendar-days', 'admin.agenda.index'],
            ['spp', 'Siswa Masih Ada Tagihan', fn () => SppBill::where('total', '>', 0)->count(), 'fa-file-invoice-dollar', 'admin.spp.index'],
        ])->filter(fn ($s) => $user->canAccess($s[0]))
            ->map(fn ($s) => [$s[1], ($s[2])(), $s[3], $s[4]])->values()->all();

        $actions = collect([
            ['berita', 'Tulis Berita', 'fa-pen-to-square', fn () => route('admin.berita.create')],
            ['prestasi', 'Tambah Prestasi', 'fa-trophy', fn () => route('admin.prestasi.create')],
            ['galeri', 'Upload Foto', 'fa-camera', fn () => route('admin.galeri.create')],
            ['agenda', 'Tambah Agenda', 'fa-calendar-plus', fn () => route('admin.agenda.create')],
            ['sdm', 'Tambah Guru', 'fa-user-plus', fn () => route('admin.sdm.create')],
            ['spmb', 'Info SPMB', 'fa-bullhorn', fn () => route('admin.settings.edit', 'spmb')],
            ['spp', 'Catat Pembayaran', 'fa-hand-holding-dollar', fn () => route('admin.spp.index')],
            ['spp-import', 'Upload Excel Tagihan', 'fa-file-excel', fn () => route('admin.spp.import')],
            ['pembayaran', 'Info Pembayaran', 'fa-money-bill-transfer', fn () => route('admin.settings.edit', 'pembayaran')],
        ])->filter(fn ($a) => $user->canAccess($a[0]))
            ->map(fn ($a) => [$a[1], $a[2], ($a[3])()])->values()->all();

        return view('admin.dashboard', [
            'stats' => $stats,
            'actions' => $actions,
            'latestPosts' => $user->canAccess('berita') ? Post::latest('published_at')->latest('id')->take(5)->get() : null,
            'upcoming' => $user->canAccess('agenda') ? Agenda::whereDate('start_date', '>=', today())->orderBy('start_date')->take(5)->get() : collect(),
            // Peringatan bila akun masih memakai password bawaan seeder.
            'usesDefaultPassword' => Hash::check('superadmin123', $request->user()->password),
        ]);
    }
}
