<?php

namespace App\Providers;

use App\Models\Program;
use App\Models\WorkUnit;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');

        // Data menu navbar (dropdown Program Madrasah & Unit Kerja) untuk layout publik.
        View::composer('partials.navbar', function ($view) {
            $view->with([
                'navPrograms' => Program::orderBy('sort_order')->get(['slug', 'menu_name', 'icon']),
                'navUnit' => WorkUnit::orderBy('sort_order')->value('slug'),
            ]);
        });

        // Cek tagihan berdasarkan NISN: batasi per IP agar data siswa tidak bisa ditebak/dikumpulkan massal.
        RateLimiter::for('spp-check', function (Request $request) {
            $tooMany = fn () => back()->withInput()->withErrors([
                'nisn' => 'Terlalu banyak pencarian. Silakan coba lagi beberapa saat lagi.',
            ]);

            return [
                Limit::perMinute(10)->by('spp-min|'.$request->ip())->response($tooMany),
                Limit::perDay(60)->by('spp-day|'.$request->ip())->response($tooMany),
            ];
        });
    }
}
