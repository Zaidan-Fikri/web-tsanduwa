@extends('layouts.app')

@section('title', 'Unit Kerja')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', ['eyebrow' => 'Bidang Kerja', 'title' => 'Unit Kerja Wakil Kepala Madrasah', 'margin' => 'mb-8'])

            <!-- Unit Tabs -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                @foreach ($units as $unit)
                    @php($isActive = $active && $active->is($unit))
                    <a href="{{ route('profil.unitkerja', $unit) }}" class="unit-tab-btn pl-2 pr-5 py-2 rounded-full font-bold text-sm border transition inline-flex items-center gap-2 {{ $isActive ? 'bg-tosca-700 text-white' : 'bg-white text-slate-700 border-slate-200' }}">
                        <span class="w-9 h-9 rounded-full overflow-hidden bg-gradient-to-br from-tosca-400 to-tosca-700 shrink-0">@if ($unit->photo)<img src="{{ media_url($unit->photo) }}" alt="" class="w-full h-full object-cover object-top">@endif</span>{{ $unit->position }}
                    </a>
                @endforeach
            </div>

            @if ($active)
                <div class="unit-content bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    <!-- Banner foto -->
                    <div class="relative h-64 sm:h-80 bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-950 flex items-end overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:18px_18px] opacity-25"></div>
                        <i class="fa-solid {{ $active->icon }} absolute -right-6 -top-6 text-[180px] text-white/5"></i>
                        <div class="relative h-full w-36 sm:w-56 lg:w-64 shrink-0 ml-4 sm:ml-12 pt-6 sm:pt-8">
                            @if ($active->photo)
                                <img src="{{ media_url($active->photo) }}" alt="{{ $active->person_name }} – {{ $active->position }}" class="w-full h-full object-cover object-top drop-shadow-2xl">
                            @endif
                        </div>
                        <div class="relative self-center px-5 sm:px-10 pb-4 text-white">
                            <span class="inline-block bg-gold-500 text-slate-950 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Wakil Kepala Madrasah</span>
                            <h3 class="text-xl sm:text-3xl font-extrabold mt-3 leading-tight">{{ $active->field_title }}</h3>
                            <p class="text-white/90 font-bold mt-3 text-lg sm:text-2xl"><i class="fa-solid fa-user-tie text-gold-400 mr-2 text-base"></i>{{ $active->person_name }}</p>
                            <p class="text-gold-400 font-semibold mt-1 text-sm sm:text-base">{{ $active->position }}</p>
                        </div>
                    </div>
                    <!-- Isi -->
                    <div class="p-6 sm:p-10">
                        <p class="text-slate-600 leading-relaxed mb-6">{{ $active->description }}</p>
                        @if (! empty($active->programs))
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Program Utama</h4>
                            <div class="grid md:grid-cols-3 gap-4">
                                @foreach ($active->programs as $item)
                                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-tosca-50 border border-tosca-100">
                                        <span class="w-8 h-8 shrink-0 rounded-lg bg-tosca-700 text-gold-400 flex items-center justify-center text-sm"><i class="fa-solid fa-check"></i></span>
                                        <span class="text-sm font-medium text-slate-700 leading-relaxed">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-center text-slate-500">Data unit kerja belum tersedia.</p>
            @endif
        </div>
    </div>
@endsection
