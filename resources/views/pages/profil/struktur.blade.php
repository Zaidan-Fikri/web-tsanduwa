@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', ['eyebrow' => 'Bagan Kerja', 'title' => 'Struktur Organisasi '.setting('school_name', 'MTs. Darul Ulum Waru')])

            <div class="bg-white p-8 rounded-3xl shadow-md border border-slate-100 text-center space-y-8">
                @forelse ($levels as $level => $members)
                    @unless ($loop->first)
                        <div class="w-0.5 h-8 bg-slate-300 mx-auto"></div>
                    @endunless

                    @if ($loop->first)
                        <!-- Top Level -->
                        <div class="flex flex-wrap justify-center gap-4">
                            @foreach ($members as $member)
                                <div class="inline-block bg-tosca-900 text-white p-4 rounded-2xl shadow-lg border-2 border-gold-400">
                                    <span class="text-xs text-gold-400 font-bold block uppercase">{{ $member->position }}</span>
                                    <h4 class="font-extrabold text-lg">{{ $member->name }}</h4>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach ($members as $member)
                                <div class="bg-tosca-50 p-4 rounded-xl border border-tosca-200 shadow-sm">
                                    <span class="text-xs text-tosca-800 font-bold block uppercase">{{ $member->position }}</span>
                                    <h5 class="font-bold text-slate-800 text-sm mt-1">{{ $member->name }}</h5>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @empty
                    <p class="text-slate-500">Struktur organisasi belum diisi.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
