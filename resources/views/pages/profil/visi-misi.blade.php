@extends('layouts.app')

@section('title', 'Visi & Misi')

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @include('partials.section-title', ['eyebrow' => 'Arah & Tujuan', 'title' => 'Visi & Misi Madrasah', 'margin' => ''])

            <div class="bg-gradient-to-br from-tosca-800 to-tosca-950 text-white rounded-3xl p-8 sm:p-10 shadow-xl border border-tosca-700 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gold-500/20 text-gold-400 flex items-center justify-center text-3xl mx-auto mb-4 border border-gold-500/30">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h2 class="text-xl font-bold uppercase text-gold-400 tracking-wider">Visi Utama</h2>
                <p class="text-xl sm:text-2xl font-extrabold mt-3 max-w-3xl mx-auto leading-relaxed">
                    "{{ setting('vision') }}"
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center space-x-3">
                    <i class="fa-solid fa-bullseye text-tosca-700"></i>
                    <span>Misi Madrasah</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($missions as $mission)
                        <div class="flex items-start space-x-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="w-8 h-8 rounded-full bg-tosca-700 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">{{ $loop->iteration }}</span>
                            <p class="text-slate-700 text-sm">{{ $mission->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
