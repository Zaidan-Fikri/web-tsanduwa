@extends('layouts.app')

@section('title', 'Berita')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', ['eyebrow' => 'Kabar Madrasah', 'title' => 'Berita Terbaru'])

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($posts as $post)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-slate-100 hover:shadow-xl transition flex flex-col">
                        @if ($post->image)
                            <img src="{{ media_url($post->image) }}" alt="{{ $post->title }}" loading="lazy" class="h-48 w-full object-cover">
                        @else
                            <div class="h-48 w-full bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-900 flex items-center justify-center text-gold-400 text-5xl"><i class="fa-solid fa-newspaper"></i></div>
                        @endif
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <span class="{{ $post->badgeClass() }} text-xs font-bold px-3 py-1 rounded-full">{{ $post->categoryLabel() }}</span>
                                <h3 class="font-bold text-slate-900 text-lg mt-3 hover:text-tosca-700 cursor-pointer" onclick="openNewsModal({{ $post->id }})">{{ $post->title }}</h3>
                                <p class="text-slate-500 text-sm mt-2 line-clamp-3">{{ $post->summary() }}</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span><i class="fa-regular fa-calendar mr-1"></i> {{ $post->dateLabel() }}</span>
                                <button type="button" onclick="openNewsModal({{ $post->id }})" class="text-tosca-700 font-bold hover:underline">Selengkapnya &rarr;</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-center text-slate-500">Belum ada berita.</p>
                @endforelse
            </div>

            @if ($posts->hasPages())
                <div class="mt-12 flex justify-center">{{ $posts->links('partials.pagination') }}</div>
            @endif
        </div>
    </div>

    @include('partials.news-templates', ['posts' => $posts])
@endsection
