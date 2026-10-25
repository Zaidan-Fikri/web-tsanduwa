<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SiteController;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik (landing page)
|--------------------------------------------------------------------------
*/

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('sambutan', 'sambutan')->name('sambutan');
        Route::get('identitas', 'identitas')->name('identitas');
        Route::get('visi-misi', 'visiMisi')->name('visimisi');
        Route::get('sejarah', 'sejarah')->name('sejarah');
        Route::get('struktur-organisasi', 'struktur')->name('struktur');
        Route::get('unit-kerja/{workUnit?}', 'unitKerja')->name('unitkerja');
        Route::get('tenaga-pendidik', 'sdm')->name('sdm');
    });

    Route::get('program-madrasah/{program?}', 'program')->name('program');
    Route::get('ekstrakurikuler', 'ekskul')->name('ekskul');
    Route::get('berita', 'berita')->name('berita');
    Route::get('berita/{post}', 'beritaShow')->name('berita.show');
    Route::get('prestasi', 'prestasi')->name('prestasi');
    Route::get('galeri', 'galeri')->name('galeri');
    Route::get('agenda', 'agenda')->name('agenda');
    Route::get('spmb', 'spmb')->name('spmb');
});

// Cek tagihan SPP oleh wali murid (dibatasi agar data siswa tidak bisa dikumpulkan massal).
Route::controller(PaymentController::class)->group(function () {
    Route::get('pembayaran', 'show')->name('pembayaran');
    Route::post('pembayaran', 'check')->middleware('throttle:spp-check')->name('pembayaran.check');
});

// Gambar upload yang tersimpan di database. Tanpa session/cookie agar ringan & bisa di-cache browser.
Route::get('media/{id}.{extension}', [MediaController::class, 'show'])
    ->whereNumber('id')
    ->where('extension', 'webp|png|jpg')
    ->withoutMiddleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        ValidateCsrfToken::class,
    ])
    ->name('media.show');

/*
|--------------------------------------------------------------------------
| Panel admin (prefix bisa diganti lewat ADMIN_PATH di .env)
|--------------------------------------------------------------------------
*/

Route::prefix(admin_path())->name('admin.')->middleware('admin.area')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
    });

    // auth.session: sesi di perangkat lain otomatis keluar saat password diganti.
    // admin.menu: Admin hanya bisa membuka menu yang dicentang di Pengguna Admin.
    Route::middleware(['auth', 'auth.session', 'admin.menu'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Pengaturan berbasis form (beranda, sambutan, identitas, sejarah, SPMB, kontak)
        Route::get('pengaturan/{group}', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('pengaturan/{group}', [Admin\SettingController::class, 'update'])->name('settings.update');

        // Data per menu
        Route::resource('slider', Admin\HeroSlideController::class)->parameters(['slider' => 'heroSlide'])->except('show');
        Route::resource('agenda', Admin\AgendaController::class)->except('show');
        Route::resource('fasilitas', Admin\FacilityController::class)->parameters(['fasilitas' => 'facility'])->except('show');
        Route::resource('misi', Admin\MissionController::class)->parameters(['misi' => 'mission'])->except('show');
        Route::resource('struktur', Admin\OrgStructureController::class)->parameters(['struktur' => 'orgStructure'])->except('show');
        Route::resource('unit-kerja', Admin\WorkUnitController::class)->parameters(['unit-kerja' => 'workUnit'])->except('show');
        Route::resource('sdm', Admin\StaffController::class)->parameters(['sdm' => 'staff'])->except('show');
        Route::resource('program', Admin\ProgramController::class)->except('show');
        Route::resource('ekskul', Admin\ExtracurricularController::class)->parameters(['ekskul' => 'extracurricular'])->except('show');
        Route::resource('berita', Admin\PostController::class)->parameters(['berita' => 'post'])->except('show');
        Route::resource('prestasi', Admin\AchievementController::class)->parameters(['prestasi' => 'achievement'])->except('show');
        Route::resource('galeri', Admin\GalleryController::class)->parameters(['galeri' => 'gallery'])->except('show');
        Route::resource('spmb-prosedur', Admin\SpmbStepController::class)->parameters(['spmb-prosedur' => 'spmbStep'])->except('show');
        Route::resource('spmb-persyaratan', Admin\SpmbRequirementController::class)->parameters(['spmb-persyaratan' => 'spmbRequirement'])->except('show');

        // Tagihan SPP (upload Excel -> preview -> simpan)
        Route::controller(Admin\SppBillController::class)->prefix('tagihan-spp')->name('spp.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('import', 'importForm')->name('import');
            Route::post('import', 'upload')->name('upload');
            Route::get('import/preview', 'preview')->name('preview');
            Route::get('kode-akses', 'codes')->name('codes');
            Route::post('import/simpan', 'commit')->name('commit');
            Route::post('import/batal', 'cancel')->name('cancel');
            Route::delete('kelas/{className}', 'destroyClass')->where('className', '[0-9A-Za-z]{1,10}')->name('destroy-class');
            Route::delete('{sppBill}', 'destroy')->name('destroy');
        });

        // Edit tagihan per siswa: catat pembayaran / cicilan, koreksi data, batalkan pembayaran
        Route::controller(Admin\SppPaymentController::class)->prefix('tagihan-spp/{sppBill}')->name('spp.')->group(function () {
            Route::get('edit', 'edit')->name('edit');
            Route::post('bayar', 'pay')->name('pay');
            Route::put('/', 'update')->name('update');
            Route::delete('pembayaran/{sppPayment}', 'cancel')->name('payment.cancel');
            Route::post('reset-kode', 'resetCode')->name('reset-code');
        });

        // Akun
        Route::get('akun', [Admin\AccountController::class, 'edit'])->name('account.edit');
        Route::put('akun', [Admin\AccountController::class, 'update'])->name('account.update');
        Route::put('akun/password', [Admin\AccountController::class, 'password'])->name('account.password');

        Route::resource('pengguna', Admin\UserController::class)
            ->parameters(['pengguna' => 'user'])
            ->except('show')
            ->middleware('superadmin');
    });
});
