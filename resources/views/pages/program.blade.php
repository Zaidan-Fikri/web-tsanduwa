@extends('layouts.app')

@section('title', $active ? $active->title : 'Program Madrasah')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', ['eyebrow' => 'Program Unggulan', 'title' => 'Program Madrasah', 'margin' => 'mb-8'])

            <div class="flex flex-wrap justify-center gap-2 mb-8">
                @foreach ($programs as $program)
                    @php($isActive = $active && $active->is($program))
                    <a href="{{ route('program', $program) }}" class="prog-tab-btn px-5 py-2.5 rounded-full font-bold text-sm border transition inline-flex items-center gap-2 {{ $isActive ? 'bg-tosca-700 text-white border-tosca-700' : 'bg-white text-slate-700 border-slate-200' }}"><i class="fa-solid {{ $program->icon }}"></i>{{ $program->menu_name }}</a>
                @endforeach
            </div>

            @if ($active)
                <div class="prog-content bg-slate-50 rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    <div class="relative bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-950 text-white px-6 sm:px-10 py-10 sm:py-14 overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:18px_18px] opacity-25"></div>
                        <i class="fa-solid {{ $active->icon }} absolute -right-4 -bottom-6 text-[160px] sm:text-[200px] text-white/10"></i>
                        <div class="relative flex items-center gap-5">
                            <span class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-2xl bg-gold-500 text-slate-950 flex items-center justify-center text-3xl sm:text-4xl shadow-lg"><i class="fa-solid {{ $active->icon }}"></i></span>
                            <div>
                                <span class="inline-block bg-white/15 text-gold-400 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Program Madrasah</span>
                                <h3 class="text-2xl sm:text-3xl font-extrabold mt-2 leading-tight">{{ $active->title }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-10">
                        <p class="text-slate-600 leading-relaxed mb-6">{{ $active->description }}</p>
                        @if (! empty($active->activities))
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Kegiatan</h4>
                            <div class="grid md:grid-cols-3 gap-4">
                                @foreach ($active->activities as $activity)
                                    <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                                        <span class="w-11 h-11 rounded-xl bg-tosca-50 text-tosca-700 flex items-center justify-center text-lg"><i class="fa-solid {{ $activity['icon'] ?? 'fa-star' }}"></i></span>
                                        <h5 class="font-bold text-slate-900 mt-4">{{ $activity['title'] ?? '' }}</h5>
                                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $activity['description'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (! empty($active->infos))
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-8 mb-3">Informasi</h4>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($active->infos as $info)
                                    <span class="inline-flex items-center gap-2 bg-white border border-tosca-100 text-tosca-800 text-sm font-semibold px-4 py-2 rounded-full"><i class="fa-solid {{ $info['icon'] ?? 'fa-circle-info' }} text-tosca-600"></i>{{ $info['text'] ?? '' }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-center text-slate-500">Program madrasah belum tersedia.</p>
            @endif
        </div>
    </div>
@endsection
