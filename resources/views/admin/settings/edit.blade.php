@extends('layouts.admin')

@section('title', $definition['title'])

@php
    $related = [
        'beranda' => [['Slider Utama', 'admin.slider.index'], ['Agenda', 'admin.agenda.index'], ['Fasilitas', 'admin.fasilitas.index']],
        'spmb' => [['Prosedur Pendaftaran', 'admin.spmb-prosedur.index'], ['Persyaratan', 'admin.spmb-persyaratan.index']],
        'visi' => [['Daftar Misi', 'admin.misi.index']],
    ][$group] ?? [];
    $preview = [
        'beranda' => route('home'), 'sambutan' => route('profil.sambutan'), 'identitas' => route('profil.identitas'),
        'visi' => route('profil.visimisi'), 'sejarah' => route('profil.sejarah'), 'spmb' => route('spmb'), 'kontak' => route('home'),
    ][$group] ?? route('home');
@endphp

@section('content')
    <x-admin.page-header :title="$definition['title']" :subtitle="$definition['description']" :icon="$definition['icon']">
        <a href="{{ $preview }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-eye"></i> Lihat Halaman</a>
    </x-admin.page-header>

    @if ($related)
        <div class="flex flex-wrap gap-2 mb-5">
            <span class="text-xs font-semibold text-slate-500 self-center mr-1">Menu terkait:</span>
            @foreach ($related as [$label, $route])
                <a href="{{ route($route) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition">{{ $label }} <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            @endforeach
        </div>
    @endif

    <x-admin.form-card :action="route('admin.settings.update', $group)" method="PUT" :files="true" submit="Simpan Perubahan">
        <div class="grid md:grid-cols-2 gap-5">
            @foreach ($definition['fields'] as $key => $field)
                @switch($field['type'])
                    @case('textarea')
                        <x-admin.textarea :name="$key" :label="$field['label']" :value="setting($key)" :rows="$field['rows'] ?? 4" :help="$field['help'] ?? null" :required="$field['required'] ?? false" class="md:col-span-2" />
                        @break
                    @case('image')
                        <x-admin.image :name="$key" :label="$field['label']" :value="setting($key)" :help="$field['help'] ?? null" class="md:col-span-2" />
                        @break
                    @default
                        <x-admin.input :name="$key" :label="$field['label']" :type="$field['type']" :value="setting($key)" :help="$field['help'] ?? null" :required="$field['required'] ?? false" />
                @endswitch
            @endforeach
        </div>
    </x-admin.form-card>
@endsection
