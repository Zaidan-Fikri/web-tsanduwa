@extends('layouts.app')

@section('title', 'Sambutan Kepala Madrasah')

@section('content')
    <!-- Banner -->
    <section class="bg-pattern relative overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-28 text-center">
            <span class="text-gold-400 font-bold text-sm tracking-widest uppercase">Sambutan Lengkap</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold mt-2">Kepala {{ setting('school_name', 'MTs. Darul Ulum Waru') }}</h1>
            <div class="w-20 h-1 bg-gold-500 mx-auto mt-4 rounded-full"></div>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10 pb-6">
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            <!-- Kartu profil -->
            <aside class="lg:col-span-4 lg:sticky lg:top-28">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    <div class="relative h-96 bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-950 overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:18px_18px] opacity-25"></div>
                        <img src="{{ asset('img/logo.png') }}" alt="" class="absolute -right-10 -top-10 w-48 h-48 opacity-10">
                        <img src="{{ media_url(setting('principal_photo'), 'img/amir_mencep.png') }}" alt="{{ setting('principal_name') }}" class="absolute inset-x-0 bottom-0 mx-auto h-[92%] w-auto max-w-none object-contain object-bottom drop-shadow-2xl">
                    </div>
                    <div class="p-6 text-center">
                        <span class="inline-block bg-gold-500 text-slate-950 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Kepala Madrasah</span>
                        <h3 class="font-extrabold text-slate-900 text-xl mt-3">{{ setting('principal_name') }}</h3>
                        <p class="text-tosca-700 text-sm font-medium mt-1">{{ setting('school_name', 'MTs. Darul Ulum Waru') }}</p>
                        <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                            <div><i class="fa-solid fa-hands-praying text-tosca-700 text-lg"></i><p class="text-[11px] font-bold text-slate-600 mt-1 uppercase tracking-wide">Beriman</p></div>
                            <div><i class="fa-solid fa-book-open text-tosca-700 text-lg"></i><p class="text-[11px] font-bold text-slate-600 mt-1 uppercase tracking-wide">Berilmu</p></div>
                            <div><i class="fa-solid fa-trophy text-gold-500 text-lg"></i><p class="text-[11px] font-bold text-slate-600 mt-1 uppercase tracking-wide">Berprestasi</p></div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Isi sambutan -->
            <article class="lg:col-span-8 bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-10 relative overflow-hidden">
                <i class="fa-solid fa-quote-right absolute right-8 top-6 text-7xl text-tosca-50"></i>
                @if (setting('greeting_opening'))
                    <p class="relative text-xl sm:text-2xl font-bold text-tosca-800 italic mb-6">{{ setting('greeting_opening') }}</p>
                @endif
                <div class="relative space-y-5 text-slate-700 leading-loose text-justify">
                    {{ paragraphs(setting('greeting_body')) }}
                    @if (setting('greeting_quote'))
                        <blockquote class="not-italic my-8 p-6 rounded-2xl bg-gradient-to-r from-tosca-700 to-tosca-900 text-white shadow-lg relative overflow-hidden">
                            <i class="fa-solid fa-quote-left text-gold-400 text-2xl"></i>
                            <p class="mt-3 text-base sm:text-lg leading-relaxed text-left">{{ setting('greeting_quote') }}</p>
                        </blockquote>
                    @endif
                    {{ paragraphs(setting('greeting_closing_body')) }}
                </div>
                @if (setting('greeting_closing'))
                    <p class="relative text-lg font-bold text-tosca-800 italic mt-8">{{ setting('greeting_closing') }}</p>
                @endif
            </article>
        </div>
    </div>
@endsection
