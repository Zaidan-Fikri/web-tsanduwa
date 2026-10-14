@extends('layouts.app')

@section('title', 'Tenaga Pendidik & Kependidikan')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', [
                'eyebrow' => 'Sumber Daya Manusia',
                'title' => 'Tenaga Pendidik & Kependidikan',
                'subtitle' => 'Guru profesional, berpengalaman, dan berdedikasi tinggi mendidik generasi masa depan.',
            ])

            <div id="sdm-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-6 gap-y-10">
                @forelse ($people as $person)
                    <div class="sdm-item group text-center">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden {{ $person->photoBgClass() }} shadow-sm group-hover:shadow-xl group-hover:-translate-y-1 transition duration-300">
                            @if ($person->photo)
                                <img src="{{ media_url($person->photo) }}" alt="{{ $person->name }}" loading="lazy" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-tosca-300 text-6xl"><i class="fa-solid fa-user"></i></div>
                            @endif
                            <span class="absolute top-3 left-3 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow {{ $person->badgeClass() }}">{{ $person->typeLabel() }}</span>
                        </div>
                        <h5 class="font-semibold text-slate-800 text-[15px] mt-4">{{ $person->name }}</h5>
                        <p class="text-[13px] text-slate-500 mt-1">{{ $person->position }}</p>
                    </div>
                @empty
                    <p class="col-span-full text-center text-slate-500">Data belum tersedia.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($people->total() > 0)
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-slate-500">Menampilkan <b class="text-slate-800">{{ $people->firstItem() }}–{{ $people->lastItem() }}</b> dari <b class="text-slate-800">{{ $people->total() }}</b> orang</p>
                    {{ $people->links('partials.pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
