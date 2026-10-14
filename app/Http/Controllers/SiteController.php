<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\HeroSlide;
use App\Models\Mission;
use App\Models\OrgStructure;
use App\Models\Post;
use App\Models\Program;
use App\Models\SpmbRequirement;
use App\Models\SpmbStep;
use App\Models\Staff;
use App\Models\WorkUnit;
use Illuminate\Database\Eloquent\Builder;

class SiteController extends Controller
{
    public function home()
    {
        $upcoming = Agenda::where(fn (Builder $q) => $q
            ->whereDate('start_date', '>=', today())
            ->orWhereDate('end_date', '>=', today()))
            ->orderBy('start_date')->take(3)->get();

        return view('pages.home', [
            'slides' => HeroSlide::where('is_active', true)->orderBy('sort_order')->get(),
            'agendas' => $upcoming->isNotEmpty() ? $upcoming : Agenda::latest('start_date')->take(3)->get()->sortBy('start_date'),
            'posts' => Post::published()->latest('published_at')->latest('id')->take(3)->get(),
            'teachers' => Staff::where('type', 'guru')->where('show_on_home', true)->orderBy('sort_order')->get(),
            'staffs' => Staff::where('type', 'tendik')->where('show_on_home', true)->orderBy('sort_order')->get(),
            'facilities' => Facility::orderBy('sort_order')->get(),
            'ekskuls' => Extracurricular::where('show_on_home', true)->orderBy('sort_order')->get(),
            'achievements' => Achievement::where('show_on_home', true)->latest('year')->latest('id')->take(3)->get(),
        ]);
    }

    public function sambutan()
    {
        return view('pages.profil.sambutan');
    }

    public function identitas()
    {
        return view('pages.profil.identitas');
    }

    public function visiMisi()
    {
        return view('pages.profil.visi-misi', [
            'missions' => Mission::orderBy('sort_order')->get(),
        ]);
    }

    public function sejarah()
    {
        return view('pages.profil.sejarah');
    }

    public function struktur()
    {
        return view('pages.profil.struktur', [
            'levels' => OrgStructure::orderBy('level')->orderBy('sort_order')->get()->groupBy('level'),
        ]);
    }

    public function unitKerja(?WorkUnit $workUnit = null)
    {
        $units = WorkUnit::orderBy('sort_order')->get();

        return view('pages.profil.unit-kerja', [
            'units' => $units,
            'active' => $workUnit ?? $units->first(),
        ]);
    }

    public function sdm()
    {
        $people = Staff::orderByRaw("CASE type WHEN 'pimpinan' THEN 1 WHEN 'guru' THEN 2 ELSE 3 END")
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(10);

        return view('pages.profil.sdm', ['people' => $people]);
    }

    public function program(?Program $program = null)
    {
        $programs = Program::orderBy('sort_order')->get();

        return view('pages.program', [
            'programs' => $programs,
            'active' => $program ?? $programs->first(),
        ]);
    }

    public function ekskul()
    {
        $ekskuls = Extracurricular::orderBy('sort_order')->get();

        return view('pages.ekstrakurikuler', [
            'ekskuls' => $ekskuls,
            'counts' => $ekskuls->countBy('category'),
        ]);
    }

    public function berita()
    {
        return view('pages.berita.index', [
            'posts' => Post::published()->latest('published_at')->latest('id')->paginate(9),
        ]);
    }

    public function beritaShow(Post $post)
    {
        abort_unless($post->is_published && $post->published_at->lte(today()), 404);

        return view('pages.berita.show', [
            'post' => $post,
            'others' => Post::published()->whereKeyNot($post->id)->latest('published_at')->take(3)->get(),
        ]);
    }

    public function prestasi()
    {
        $achievements = Achievement::latest('year')->latest('id')->get();

        return view('pages.prestasi', [
            'achievements' => $achievements,
            'featured' => $achievements->firstWhere('is_featured', true),
            'counts' => $achievements->countBy('level'),
            'firstPlaces' => $achievements->filter->isFirstPlace()->count(),
            'olimpiade' => Program::where('slug', 'olimpiade')->first(),
        ]);
    }

    public function galeri()
    {
        $items = Gallery::orderBy('sort_order')->latest('id')->get();

        return view('pages.galeri', [
            'photos' => $items->where('type', 'foto'),
            'videos' => $items->where('type', 'video'),
        ]);
    }

    public function agenda()
    {
        return view('pages.agenda', [
            'agendas' => Agenda::orderByDesc('start_date')->paginate(15),
        ]);
    }

    public function spmb()
    {
        $requirements = SpmbRequirement::orderBy('sort_order')->get();

        return view('pages.spmb', [
            'steps' => SpmbStep::orderBy('sort_order')->get(),
            'required' => $requirements->where('is_required', true),
            'optional' => $requirements->where('is_required', false),
        ]);
    }
}
