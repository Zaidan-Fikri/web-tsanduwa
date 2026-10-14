@extends('layouts.app')

@section('title', 'SPMB '.setting('spmb_year'))

@php
    $waUrl = wa_link(setting('contact_whatsapp'), setting('spmb_wa_message'));
    $brochure = setting('spmb_brochure');
@endphp

@section('content')
    <!-- Banner -->
    <section class="bg-pattern relative overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-tosca-400/20 blur-3xl"></div>
        <div class="absolute right-0 top-0 w-[28rem] h-[28rem] rounded-full bg-gold-500/15 blur-3xl"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 lg:pt-6 grid lg:grid-cols-2 gap-6 lg:gap-10 items-center">
            <div class="text-center lg:text-left lg:py-14">
                @if (setting('spmb_badge'))
                    <span class="inline-flex items-center gap-2 bg-gold-500 text-slate-950 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider"><i class="fa-solid fa-bullhorn"></i> {{ setting('spmb_badge') }}</span>
                @endif
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mt-5 leading-tight">SPMB <span class="text-gold-400">{{ setting('spmb_year') }}</span></h1>
                <p class="text-tosca-100 mt-4 max-w-xl mx-auto lg:mx-0 sm:text-lg leading-relaxed">{{ setting('spmb_description') }}</p>
                <p class="text-gold-400 font-semibold text-sm mt-5 tracking-wider uppercase">Beriman • Berilmu • Berprestasi</p>
                <div class="mt-7 flex flex-wrap gap-3 justify-center lg:justify-start">
                    <a href="#prosedur-spmb" class="bg-gold-500 hover:bg-gold-600 text-slate-950 font-bold px-6 py-3 rounded-xl shadow-lg transition inline-flex items-center gap-2 hover:-translate-y-0.5">Lihat Prosedur <i class="fa-solid fa-arrow-down"></i></a>
                    @if (setting('contact_whatsapp'))
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur font-semibold px-6 py-3 rounded-xl transition inline-flex items-center gap-2"><i class="fa-brands fa-whatsapp text-lg"></i> Tanya Panitia</a>
                    @endif
                </div>
            </div>
            <div class="relative flex justify-center lg:justify-end self-end">
                <img src="{{ media_url(setting('spmb_photo'), 'img/foto_spmb.png') }}" alt="SPMB {{ setting('school_name') }}" class="relative w-auto h-[360px] sm:h-[440px] lg:h-[520px] max-w-full object-contain object-bottom drop-shadow-[0_20px_35px_rgba(0,0,0,0.35)]">
            </div>
        </div>
    </section>

    <!-- Ajakan brosur -->
    @if ($brochure)
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 relative z-10">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-50 via-white to-tosca-50 border border-gold-400/40 shadow-lg px-5 sm:px-8 py-5 flex flex-col md:flex-row items-center gap-4 md:gap-6">
                <div class="absolute -left-6 -top-6 w-24 h-24 rounded-full bg-gold-400/20"></div>
                <div class="absolute -right-8 -bottom-10 w-32 h-32 rounded-full bg-tosca-200/40"></div>
                <span class="relative w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br from-gold-400 to-gold-600 text-white flex items-center justify-center text-xl shadow-md"><i class="fa-solid fa-file-image"></i></span>
                <div class="relative flex-1 text-center md:text-left">
                    <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">Semua info SPMB dalam satu brosur!</h3>
                    <p class="text-sm text-slate-600 mt-0.5">Visi misi, program unggulan, ekstrakurikuler, hingga alur pendaftaran. Simpan dan bagikan ke keluarga Anda.</p>
                </div>
                <button type="button" onclick="openBrosurModal()" class="relative shrink-0 inline-flex items-center gap-2 bg-tosca-700 hover:bg-tosca-800 text-white font-bold text-sm px-5 py-3 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition">
                    <i class="fa-solid fa-download text-gold-400"></i> Lihat & Download Brosur
                </button>
            </div>
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-16">

        <!-- Prosedur Pendaftaran -->
        @if ($steps->isNotEmpty())
            <section id="prosedur-spmb" class="scroll-mt-28">
                <div class="text-center mb-10">
                    <span class="text-tosca-700 font-bold text-sm tracking-widest uppercase">Alur SPMB</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Prosedur Pendaftaran</h2>
                    <div class="w-20 h-1 bg-gold-500 mx-auto mt-3 rounded-full"></div>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($steps as $step)
                        <div class="relative bg-white rounded-2xl p-6 pt-10 shadow-md border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition">
                            <span class="absolute -top-5 left-6 w-10 h-10 rounded-full {{ $step->is_highlight ? 'bg-gold-500 text-slate-950' : 'bg-tosca-700 text-white' }} font-extrabold flex items-center justify-center ring-4 ring-slate-50">{{ $loop->iteration }}</span>
                            <div class="w-14 h-14 rounded-xl {{ $step->is_highlight ? 'bg-amber-50 text-gold-600' : 'bg-tosca-50 text-tosca-700' }} flex items-center justify-center text-2xl mb-4"><i class="fa-solid {{ $step->icon }}"></i></div>
                            <h3 class="font-bold text-slate-900 uppercase tracking-wide">{{ $step->title }}</h3>
                            <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $step->description }}</p>
                            @if ($step->badge)
                                <span class="inline-flex items-center gap-1.5 mt-4 bg-tosca-50 text-tosca-700 font-bold text-sm px-3 py-1 rounded-full"><i class="fa-regular fa-clock"></i> {{ $step->badge }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Persyaratan Pendaftaran -->
        @if ($required->isNotEmpty() || $optional->isNotEmpty())
            <section class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-tosca-700 text-white px-6 sm:px-10 py-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl sm:text-2xl font-extrabold flex items-center gap-3"><i class="fa-solid fa-folder-open text-gold-400"></i> Persyaratan Pendaftaran</h2>
                </div>
                <div class="p-6 sm:p-10">
                    <!-- Progres kesiapan -->
                    <div class="mb-8 p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <span class="font-bold text-slate-800"><i class="fa-solid fa-list-check text-tosca-700 mr-2"></i>Cek Kesiapan Berkas Anda</span>
                            <span class="text-sm font-semibold text-slate-600"><span id="syarat-count">0</span> / {{ $required->count() }} berkas wajib siap</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-200 overflow-hidden"><div id="syarat-bar" class="h-full w-0 bg-tosca-600 rounded-full transition-all duration-500"></div></div>
                        <p id="syarat-status" class="text-sm text-slate-500 mt-3">Centang berkas yang sudah Anda siapkan.</p>
                    </div>

                    @if ($required->isNotEmpty())
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Wajib</h3>
                        <div class="grid sm:grid-cols-2 gap-3">
                            @foreach ($required as $item)
                                <label class="syarat-item"><input type="checkbox" class="syarat-cb syarat-wajib" onchange="updateSyarat()"><span class="syarat-box"><i class="fa-solid fa-check"></i></span><span class="syarat-label">{{ $item->name }}</span></label>
                            @endforeach
                        </div>
                    @endif

                    @if ($optional->isNotEmpty())
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-8 mb-4">Tambahan (bila ada)</h3>
                        <div class="grid sm:grid-cols-2 gap-3">
                            @foreach ($optional as $item)
                                <label class="syarat-item"><input type="checkbox" class="syarat-cb" onchange="updateSyarat()"><span class="syarat-box"><i class="fa-solid fa-check"></i></span><span class="syarat-label">{{ $item->name }}</span></label>
                            @endforeach
                        </div>
                    @endif

                    <div class="text-right mt-6"><button type="button" onclick="resetSyarat()" class="text-sm font-semibold text-slate-500 hover:text-tosca-700 transition"><i class="fa-solid fa-rotate-left mr-1"></i> Reset centang</button></div>
                </div>
            </section>
        @endif

        <!-- Info Lokasi -->
        <section class="bg-pattern rounded-3xl p-8 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 shrink-0 rounded-2xl bg-gold-500 text-slate-950 flex items-center justify-center text-2xl"><i class="fa-solid fa-school"></i></div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold">Pendaftaran Langsung di Kantor Madrasah</h3>
                    <p class="text-tosca-100 mt-1 text-sm sm:text-base"><i class="fa-solid fa-location-dot text-gold-400 mr-1"></i> {{ setting('contact_address') }} @if (setting('spmb_hours')) &nbsp;•&nbsp; <i class="fa-regular fa-clock text-gold-400 mr-1"></i> {{ setting('spmb_hours') }} @endif</p>
                </div>
            </div>
            @if (setting('contact_whatsapp'))
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="shrink-0 bg-[#25D366] hover:bg-[#1ebe5a] text-white font-bold px-6 py-3 rounded-xl shadow-lg transition inline-flex items-center gap-2 hover:-translate-y-0.5"><i class="fa-brands fa-whatsapp text-xl"></i> Tanya via WhatsApp</a>
            @endif
        </section>
    </div>

    <!-- Brosur Modal -->
    @if ($brochure)
        <div id="brosur-modal" onclick="if (event.target === this) closeBrosurModal()" class="fixed inset-0 z-[60] bg-slate-900/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[92vh]">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-tosca-50 text-tosca-700 flex items-center justify-center"><i class="fa-solid fa-file-image"></i></span>
                        <div>
                            <h3 class="font-extrabold text-slate-900 leading-tight">Brosur SPMB</h3>
                            <p class="text-xs text-slate-500">{{ setting('school_name') }} · TA {{ setting('spmb_year') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeBrosurModal()" aria-label="Tutup" class="w-9 h-9 rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <div class="bg-slate-100 p-4 overflow-y-auto flex-1">
                    <img src="{{ media_url($brochure) }}" alt="Brosur SPMB {{ setting('school_name') }}" loading="lazy" class="w-full h-auto rounded-xl shadow-md">
                </div>
                <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeBrosurModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Tutup</button>
                    <a href="{{ media_url($brochure) }}" download="Brosur-SPMB-MTs-Darul-Ulum-Waru.{{ pathinfo($brochure, PATHINFO_EXTENSION) ?: 'jpg' }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow transition inline-flex items-center gap-2"><i class="fa-solid fa-download text-gold-400"></i> Download</a>
                </div>
            </div>
        </div>
    @endif
@endsection
