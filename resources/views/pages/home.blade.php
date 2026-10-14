@extends('layouts.app')

@section('content')
    <!-- Hero Slider -->
    <section class="relative h-[85vh] min-h-[600px] bg-tosca-950 text-white overflow-hidden">
        @foreach ($slides as $slide)
            <div class="hero-slide absolute inset-0 transition-opacity duration-1000 {{ $loop->first ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                @if ($slide->image)
                    <img src="{{ media_url($slide->image) }}" alt="" class="hero-img w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-950/55 to-tosca-950/10"></div>
                <div class="absolute inset-0 flex items-center pb-56">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl">
                            <h3 class="text-3xl sm:text-4xl lg:text-[40px] font-extrabold leading-snug mb-3">{{ $slide->title }}</h3>
                            @if ($slide->description)
                                <p class="text-slate-100 sm:text-lg leading-relaxed mb-6 line-clamp-3">{{ $slide->description }}</p>
                            @endif
                            @if ($slide->button_url)
                                <a href="{{ url($slide->button_url) }}" class="bg-tosca-600 hover:bg-tosca-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition inline-flex items-center gap-2 hover:-translate-y-0.5">{{ $slide->button_text ?: 'Selengkapnya' }} <i class="fa-regular fa-circle-right"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Dots -->
        @if ($slides->count() > 1)
            <div class="absolute bottom-60 inset-x-0 z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-2">
                    @foreach ($slides as $slide)
                        <button type="button" onclick="heroShow({{ $loop->index }})" aria-label="Slide {{ $loop->iteration }}" class="hero-dot h-3 rounded-full transition-all {{ $loop->first ? 'bg-gold-400 w-8' : 'bg-white/50 w-3' }}"></button>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- Intro: Sambutan + Statistik (menumpuk di atas slider) -->
    <section class="relative z-20 -mt-52">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-3 text-white text-3xl mb-5">
                <button type="button" onclick="heroGo(-1)" aria-label="Sebelumnya" class="hover:text-gold-400 hover:scale-110 transition"><i class="fa-regular fa-circle-left"></i></button>
                <button type="button" onclick="heroGo(1)" aria-label="Berikutnya" class="hover:text-gold-400 hover:scale-110 transition"><i class="fa-regular fa-circle-right"></i></button>
            </div>
            <div class="bg-white rounded-3xl shadow-[0_0_30px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden flex flex-col sm:flex-row">
                <div class="relative w-full h-80 sm:w-56 sm:h-auto sm:min-h-[320px] lg:w-64 shrink-0 bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-900 overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:18px_18px] opacity-30"></div>
                    <img src="{{ media_url(setting('principal_photo'), 'img/amir_mencep.png') }}" alt="Kepala Madrasah" class="absolute inset-x-0 bottom-0 mx-auto h-[94%] w-auto max-w-none object-contain object-bottom drop-shadow-xl">
                </div>
                <div class="flex-1 p-6 sm:p-10 grid lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-7 text-center sm:text-left">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 mb-2">Sambutan Kepala Madrasah</h3>
                            <h4 class="font-semibold text-slate-700 mb-2">{{ setting('principal_name') }}</h4>
                            <p class="text-slate-600 text-sm leading-relaxed mb-3">{{ setting('greeting_short') }}</p>
                            <a href="{{ route('profil.sambutan') }}" class="bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-semibold px-5 py-2 rounded-full transition inline-flex items-center gap-2 hover:-translate-y-0.5">Selengkapnya <i class="fa-regular fa-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="lg:col-span-5">
                        <h3 class="text-xl font-extrabold text-slate-900 text-center">Statistik Data Madrasah</h3>
                        <div class="grid grid-cols-3 mt-6 text-center divide-x divide-slate-200">
                            <div class="px-2"><div class="text-2xl sm:text-3xl font-extrabold text-tosca-700">{{ setting('stat_guru', '0') }}</div><div class="text-[11px] sm:text-xs font-light tracking-[2px] uppercase text-slate-600 mt-1">Guru dan Tenaga Kependidikan</div></div>
                            <div class="px-2"><div class="text-2xl sm:text-3xl font-extrabold text-tosca-700">{{ setting('stat_siswa', '0') }}</div><div class="text-[11px] sm:text-xs font-light tracking-[2px] uppercase text-slate-600 mt-1">Peserta Didik</div></div>
                            <div class="px-2"><div class="text-2xl sm:text-3xl font-extrabold text-tosca-700">{{ setting('stat_rombel', '0') }}</div><div class="text-[11px] sm:text-xs font-light tracking-[2px] uppercase text-slate-600 mt-1">Rombel</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Profil -->
    @if (setting('video_url'))
        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Video Profil Madrasah</h3>
                    <p class="text-slate-500 mt-2">Mengenal lebih dekat kegiatan dan suasana belajar di MTs. Darul Ulum Waru.</p>
                </div>
                <a href="{{ setting('video_url') }}" target="_blank" rel="noopener noreferrer" class="relative block aspect-video rounded-2xl overflow-hidden bg-tosca-950 shadow-[0_12px_34px_rgba(15,37,54,0.10)] group">
                    @if (setting('video_thumbnail'))
                        <img src="{{ media_url(setting('video_thumbnail')) }}" alt="Video profil" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition duration-500">
                    @endif
                    <span class="absolute inset-0 flex items-center justify-center">
                        <span class="w-20 h-20 rounded-full bg-red-600 text-white flex items-center justify-center text-3xl shadow-2xl group-hover:scale-110 transition"><i class="fa-solid fa-play ml-1"></i></span>
                    </span>
                    <span class="absolute bottom-0 inset-x-0 p-5 bg-gradient-to-t from-tosca-950 to-transparent text-white font-semibold"><i class="fa-brands fa-youtube text-red-500 mr-2"></i>Tonton di YouTube MTs. Darul Ulum Waru</span>
                </a>
            </div>
        </section>
    @endif

    <!-- Agenda -->
    @if ($agendas->isNotEmpty())
        <section class="pb-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Agenda</h3>
                    <p class="text-slate-500 mt-2">Agenda kegiatan dan aktivitas sesuai dengan kalender pendidikan</p>
                </div>
                <div class="space-y-4">
                    @foreach ($agendas as $agenda)
                        @include('partials.agenda-item')
                    @endforeach
                </div>
                <div class="text-center mt-8"><a href="{{ route('agenda') }}" class="bg-tosca-700 hover:bg-tosca-800 text-white font-semibold px-6 py-2.5 rounded-full transition inline-block hover:-translate-y-0.5">Selengkapnya</a></div>
            </div>
        </section>
    @endif

    <!-- Berita -->
    <section class="py-14 bg-tosca-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-start gap-4 mb-8">
                <div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Berita</h3>
                    <p class="text-slate-500 mt-2">Berita terbaru madrasah kami</p>
                </div>
                <a href="{{ route('berita') }}" class="shrink-0 bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-semibold px-3 lg:px-5 py-2 rounded-full transition inline-flex items-center gap-2"><span class="hidden lg:inline">Selengkapnya</span><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @forelse ($posts as $post)
                    <article onclick="openNewsModal({{ $post->id }})" class="cursor-pointer bg-white rounded-[18px] overflow-hidden shadow-[0_14px_40px_rgba(15,37,54,0.08)] hover:-translate-y-1.5 hover:shadow-[0_22px_55px_rgba(15,37,54,0.15)] transition duration-300 group">
                        <div class="aspect-[680/554] overflow-hidden bg-tosca-100">
                            @if ($post->image)
                                <img src="{{ media_url($post->image) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                        </div>
                        <div class="px-4 pt-4 text-xs text-slate-500">{{ $post->dateLabel() }}</div>
                        <h4 class="px-4 pt-2 pb-5 font-semibold text-slate-900 group-hover:text-tosca-700">{{ $post->title }}</h4>
                    </article>
                @empty
                    <p class="md:col-span-3 text-center text-slate-500">Belum ada berita.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Tenaga Pendidik -->
    @if ($teachers->isNotEmpty())
        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-start gap-4 mb-8">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Tenaga Pendidik</h3>
                        <p class="text-slate-500 mt-2">Tenaga pendidik pada madrasah kami</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <button type="button" onclick="slideTrack('guru-track',-1)" aria-label="Sebelumnya" class="text-tosca-700 text-2xl hover:scale-110 transition"><i class="fa-regular fa-circle-left"></i></button>
                        <button type="button" onclick="slideTrack('guru-track',1)" aria-label="Berikutnya" class="text-tosca-700 text-2xl hover:scale-110 transition"><i class="fa-regular fa-circle-right"></i></button>
                        <a href="{{ route('profil.sdm') }}" class="bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-semibold px-3 lg:px-5 py-2 rounded-full transition inline-flex items-center gap-2"><span class="hidden lg:inline">Selengkapnya</span><i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div id="guru-track" class="slider-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                    @foreach ($teachers as $person)
                        <div class="person-card"><div class="person-photo">@if ($person->photo)<img src="{{ media_url($person->photo) }}" alt="{{ $person->name }}" loading="lazy">@endif</div><h5>{{ $person->name }}</h5><p>{{ $person->position }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Tenaga Kependidikan -->
    @if ($staffs->isNotEmpty())
        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-start gap-4 mb-8">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Tenaga Kependidikan</h3>
                        <p class="text-slate-500 mt-2">Staf tenaga kependidikan pada madrasah kami</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <button type="button" onclick="slideTrack('tendik-track',-1)" aria-label="Sebelumnya" class="text-tosca-700 text-2xl hover:scale-110 transition"><i class="fa-regular fa-circle-left"></i></button>
                        <button type="button" onclick="slideTrack('tendik-track',1)" aria-label="Berikutnya" class="text-tosca-700 text-2xl hover:scale-110 transition"><i class="fa-regular fa-circle-right"></i></button>
                        <a href="{{ route('profil.sdm') }}" class="bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-semibold px-3 lg:px-5 py-2 rounded-full transition inline-flex items-center gap-2"><span class="hidden lg:inline">Selengkapnya</span><i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div id="tendik-track" class="slider-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                    @foreach ($staffs as $person)
                        <div class="person-card"><div class="person-photo">@if ($person->photo)<img src="{{ media_url($person->photo) }}" alt="{{ $person->name }}" loading="lazy">@endif</div><h5>{{ $person->name }}</h5><p>{{ $person->position }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Fasilitas -->
    @if ($facilities->isNotEmpty())
        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-start gap-4 mb-8">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Fasilitas Madrasah</h3>
                        <p class="text-slate-500 mt-2">Fasilitas madrasah yang kami miliki</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0 text-tosca-700 text-3xl">
                        <button type="button" onclick="slideTrack('fasilitas-track',-1)" aria-label="Sebelumnya" class="hover:scale-110 transition"><i class="fa-regular fa-circle-left"></i></button>
                        <button type="button" onclick="slideTrack('fasilitas-track',1)" aria-label="Berikutnya" class="hover:scale-110 transition"><i class="fa-regular fa-circle-right"></i></button>
                    </div>
                </div>
                <div id="fasilitas-track" class="slider-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                    @foreach ($facilities as $facility)
                        <div class="fasilitas-card">
                            <div class="fasilitas-img">@if ($facility->image)<img src="{{ media_url($facility->image) }}" alt="{{ $facility->name }}" loading="lazy">@endif</div>
                            <div class="fasilitas-body">
                                <h5>{{ $facility->name }}</h5>
                                @if ($facility->description)
                                    <p class="text-sm text-tosca-100 leading-relaxed -mt-2 mb-3 line-clamp-3">{{ $facility->description }}</p>
                                @endif
                                <span>Selengkapnya <i class="fa-solid fa-arrow-right ml-1"></i></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Ekstrakurikuler -->
    @if ($ekskuls->isNotEmpty())
        <section class="pb-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-start gap-4 mb-8">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Ekstrakurikuler</h3>
                        <p class="text-slate-500 mt-2">Ekstrakurikuler yang ada di madrasah kami</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0 text-tosca-700 text-2xl">
                        <button type="button" onclick="slideTrack('ekskul-track',-1)" aria-label="Sebelumnya" class="hover:scale-110 transition"><i class="fa-regular fa-circle-left"></i></button>
                        <button type="button" onclick="slideTrack('ekskul-track',1)" aria-label="Berikutnya" class="hover:scale-110 transition"><i class="fa-regular fa-circle-right"></i></button>
                    </div>
                </div>
                <div id="ekskul-track" class="slider-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                    @foreach ($ekskuls as $ekskul)
                        <a href="{{ route('ekskul') }}" class="ekskul-card">
                            <div class="ekskul-img">
                                @if ($ekskul->image)
                                    <img src="{{ media_url($ekskul->image) }}" alt="{{ $ekskul->name }}" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-900 flex items-center justify-center text-gold-400 text-5xl"><i class="fa-solid {{ $ekskul->icon }}"></i></div>
                                @endif
                            </div>
                            <h5>{{ $ekskul->name }}</h5>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Prestasi -->
    @if ($achievements->isNotEmpty())
        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-7">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Prestasi Siswa & Madrasah</h3>
                    <p class="text-slate-500 mt-2">Capaian membanggakan siswa dan madrasah kami di berbagai ajang</p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($achievements as $achievement)
                        <a href="{{ route('prestasi') }}" class="prestasi-card">
                            <div class="prestasi-foto">
                                @if ($achievement->image)
                                    <img src="{{ media_url($achievement->image) }}" alt="{{ $achievement->title }}" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-tosca-600 via-tosca-800 to-tosca-950 flex items-center justify-center text-gold-400 text-6xl"><i class="fa-solid {{ $achievement->icon }}"></i></div>
                                @endif
                                <span class="prestasi-juara"><i class="fa-solid fa-award"></i> {{ $achievement->rank }}</span>
                            </div>
                            <div class="prestasi-body"><span class="prestasi-tingkat">{{ $achievement->levelLabel() }}</span><h5>{{ $achievement->title }}</h5><div class="prestasi-meta"><span><i class="fa-regular fa-user"></i> {{ $achievement->winner }}</span><span><i class="fa-regular fa-calendar"></i> {{ $achievement->year }}</span></div></div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-8"><a href="{{ route('prestasi') }}" class="bg-tosca-700 hover:bg-tosca-800 text-white font-semibold px-6 py-2.5 rounded-full transition inline-block hover:-translate-y-0.5">Selengkapnya</a></div>
            </div>
        </section>
    @endif

    @include('partials.news-templates', ['posts' => $posts])
@endsection
