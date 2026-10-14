@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', [
                'eyebrow' => 'Kalender Pendidikan',
                'title' => 'Agenda Madrasah',
                'subtitle' => 'Agenda kegiatan dan aktivitas sesuai dengan kalender pendidikan',
            ])

            <div class="space-y-4">
                @forelse ($agendas as $agenda)
                    @include('partials.agenda-item')
                @empty
                    <p class="text-center text-slate-500">Belum ada agenda.</p>
                @endforelse
            </div>

            @if ($agendas->hasPages())
                <div class="mt-10 flex justify-center">{{ $agendas->links('partials.pagination') }}</div>
            @endif
        </div>
    </div>
@endsection
