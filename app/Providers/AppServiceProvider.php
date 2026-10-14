<?php

namespace App\Providers;

use App\Models\Program;
use App\Models\WorkUnit;
use Carbon\Carbon;
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
    }
}
