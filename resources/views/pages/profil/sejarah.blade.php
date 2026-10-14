@extends('layouts.app')

@section('title', 'Sejarah Madrasah')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white p-8 sm:p-12 rounded-3xl shadow-md border border-slate-100">
            @include('partials.section-title', ['eyebrow' => 'Jejak Langkah', 'title' => 'Sejarah Singkat Madrasah', 'margin' => 'mb-8'])
            <div class="space-y-6 text-slate-700 leading-relaxed text-justify">
                {{ paragraphs(setting('history')) }}
            </div>
        </div>
    </div>
@endsection
