<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.dashboard', [
            'stats' => [
                ['Berita', Post::count(), 'fa-newspaper', 'admin.berita.index'],
                ['Prestasi', Achievement::count(), 'fa-trophy', 'admin.prestasi.index'],
                ['Guru & Tendik', Staff::count(), 'fa-chalkboard-user', 'admin.sdm.index'],
                ['Ekstrakurikuler', Extracurricular::count(), 'fa-person-running', 'admin.ekskul.index'],
                ['Galeri', Gallery::count(), 'fa-images', 'admin.galeri.index'],
                ['Agenda', Agenda::count(), 'fa-calendar-days', 'admin.agenda.index'],
            ],
            'latestPosts' => Post::latest('published_at')->latest('id')->take(5)->get(),
            'upcoming' => Agenda::whereDate('start_date', '>=', today())->orderBy('start_date')->take(5)->get(),
            // Peringatan bila akun masih memakai password bawaan seeder.
            'usesDefaultPassword' => Hash::check('superadmin123', $request->user()->password),
        ]);
    }
}
