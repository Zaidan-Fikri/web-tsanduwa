@props(['name', 'label', 'value' => null, 'help' => null, 'required' => false, 'removable' => true, 'maxSize' => 1920])

@php($id = 'f_'.$name)
@php($url = media_url($value))
<div {{ $attributes->only('class') }} data-image-field data-max-size="{{ (int) $maxSize }}">
    <span class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }} @if ($required)<span class="text-rose-500">*</span>@endif</span>
    <div class="flex flex-col sm:flex-row gap-4 items-start">
        <div class="w-full sm:w-44 aspect-[4/3] shrink-0 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center relative">
            <img data-preview @if ($url) src="{{ $url }}" @endif alt="" class="w-full h-full object-cover {{ $url ? '' : 'hidden' }}">
            <span data-placeholder class="text-slate-300 text-4xl {{ $url ? 'hidden' : '' }}"><i class="fa-regular fa-image"></i></span>
        </div>
        <div class="flex-1 space-y-2">
            <label for="{{ $id }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-tosca-50 text-tosca-800 border border-tosca-200 text-sm font-bold cursor-pointer hover:bg-tosca-100 transition">
                <i class="fa-solid fa-cloud-arrow-up"></i> {{ $url ? 'Ganti Gambar' : 'Pilih Gambar' }}
            </label>
            <input id="{{ $id }}" name="{{ $name }}" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" data-image-input>
            <p data-filename class="text-xs text-slate-500">Format JPG, PNG, atau WEBP. Otomatis dikompres sebelum diupload.</p>
            @if ($help)
                <p class="text-xs text-slate-500">{{ $help }}</p>
            @endif
            @if ($removable && $url)
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-rose-600 cursor-pointer">
                    <input type="checkbox" name="remove_{{ $name }}" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500"> Hapus gambar ini
                </label>
            @endif
            @error($name)
                <p class="text-xs font-semibold text-rose-600"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
