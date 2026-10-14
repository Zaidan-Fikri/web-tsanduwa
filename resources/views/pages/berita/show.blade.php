@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->summary(155))

@section('content')
    <!-- Banner -->
    <section class="bg-pattern relative overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-28 text-center">
            <span class="text-gold-400 font-bold text-sm tracking-widest uppercase">{{ $post->categoryLabel() }}</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold mt-2 leading-tight">{{ $post->title }}</h1>
            <p class="text-tosca-100 mt-3 text-sm"><i class="fa-regular fa-calendar mr-1 text-gold-400"></i> {{ $post->dateLabel() }}</p>
            <div class="w-20 h-1 bg-gold-500 mx-auto mt-4 rounded-full"></div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10">
        <article class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            @if ($post->image)
                <img src="{{ media_url($post->image) }}" alt="{{ $post->title }}" class="w-full max-h-[460px] object-cover">
            @endif
            <div class="p-6 sm:p-10 space-y-5 text-slate-700 leading-loose text-justify">
                {{ paragraphs($post->content) }}
            </div>
        </article>

        <div class="mt-6">
            <a href="{{ route('berita') }}" class="text-tosca-700 font-bold text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar berita</a>
        </div>

        @if ($others->isNotEmpty())
            <h3 class="text-2xl font-extrabold text-slate-900 mt-12 mb-6">Berita Lainnya</h3>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($others as $other)
                    <a href="{{ route('berita.show', $other) }}" class="bg-white rounded-[18px] overflow-hidden shadow-[0_14px_40px_rgba(15,37,54,0.08)] hover:-translate-y-1.5 hover:shadow-[0_22px_55px_rgba(15,37,54,0.15)] transition duration-300 group">
                        <div class="aspect-[680/554] overflow-hidden bg-tosca-100">
                            @if ($other->image)
                                <img src="{{ media_url($other->image) }}" alt="" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                        </div>
                        <div class="px-4 pt-4 text-xs text-slate-500">{{ $other->dateLabel() }}</div>
                        <h4 class="px-4 pt-2 pb-5 font-semibold text-slate-900 group-hover:text-tosca-700">{{ $other->title }}</h4>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
