@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Item Galeri' : 'Tambah Item Galeri')

@php($type = old('type', $item->type))

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Item Galeri' : 'Tambah ke Galeri'" subtitle="Galeri foto & video" icon="fa-photo-film" />

    <x-admin.form-card :action="$item->exists ? route('admin.galeri.update', $item) : route('admin.galeri.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.galeri.index', ['type' => $item->type])" :files="true">
        <div>
            <span class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis</span>
            <div class="grid grid-cols-2 gap-3 max-w-md">
                @foreach (['foto' => ['Foto', 'fa-image'], 'video' => ['Video YouTube', 'fa-brands fa-youtube']] as $key => [$label, $icon])
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-tosca-300 has-[:checked]:bg-tosca-50 has-[:checked]:border-tosca-500 transition">
                        <input type="radio" name="type" value="{{ $key }}" @checked($type === $key) onchange="switchGalleryType(this.value)" class="text-tosca-700 focus:ring-tosca-600">
                        <i class="{{ str_contains($icon, 'fa-brands') ? $icon : 'fa-solid '.$icon }} text-tosca-700"></i>
                        <span class="text-sm font-bold text-slate-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <x-admin.input name="title" label="Judul / Keterangan" :value="$item->title" required maxlength="200" />

        <div data-gallery-type="foto" class="{{ $type === 'foto' ? '' : 'hidden' }}">
            <x-admin.image :max-size="1600" name="image" label="Foto" :value="$item->image" :required="! $item->image" :removable="false" />
        </div>
        <div data-gallery-type="video" class="{{ $type === 'video' ? '' : 'hidden' }}">
            <x-admin.input name="youtube_url" label="Link Video YouTube" :value="$item->youtube_id ? 'https://www.youtube.com/watch?v='.$item->youtube_id : null" placeholder="https://www.youtube.com/watch?v=..." help="Salin link dari tombol Bagikan (Share) di YouTube." />
        </div>

        <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" class="md:w-1/3" />
    </x-admin.form-card>

    @push('scripts')
        <script>
            function switchGalleryType(type) {
                document.querySelectorAll('[data-gallery-type]').forEach(el => el.classList.toggle('hidden', el.dataset.galleryType !== type));
            }
        </script>
    @endpush
@endsection
