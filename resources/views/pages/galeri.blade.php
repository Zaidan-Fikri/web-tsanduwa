@extends('layouts.app')

@section('title', 'Galeri')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', ['eyebrow' => 'Dokumentasi', 'title' => 'Galeri Kegiatan Madrasah', 'margin' => 'mb-8'])

            <!-- Gallery Category Tabs -->
            <div class="flex justify-center space-x-4 mb-8">
                <button type="button" onclick="switchGallery('foto')" id="gal-btn-foto" class="px-6 py-2 rounded-full font-bold text-sm bg-tosca-700 text-white shadow">Galeri Foto</button>
                <button type="button" onclick="switchGallery('video')" id="gal-btn-video" class="px-6 py-2 rounded-full font-bold text-sm bg-slate-200 text-slate-700 hover:bg-slate-300">Galeri Video</button>
            </div>

            <!-- Photo Gallery Container -->
            <div id="gallery-foto-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($photos as $photo)
                    <div class="relative group rounded-2xl overflow-hidden shadow-md">
                        <img src="{{ media_url($photo->image) }}" alt="{{ $photo->title }}" loading="lazy" class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
                        <div class="absolute inset-0 bg-tosca-950/70 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white p-4 text-center">
                            <span class="font-bold text-sm">{{ $photo->title }}</span>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-slate-500">Belum ada foto.</p>
                @endforelse
            </div>

            <!-- Video Gallery Container -->
            <div id="gallery-video-container" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($videos as $video)
                    <div class="bg-white p-4 rounded-2xl shadow-md border border-slate-100">
                        <div class="aspect-video bg-slate-900 rounded-xl flex items-center justify-center relative overflow-hidden group">
                            <iframe class="w-full h-full rounded-xl" src="https://www.youtube.com/embed/{{ $video->youtube_id }}" title="{{ $video->title }}" loading="lazy" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base mt-3">{{ $video->title }}</h4>
                    </div>
                @empty
                    <p class="col-span-full text-center text-slate-500">Belum ada video.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
