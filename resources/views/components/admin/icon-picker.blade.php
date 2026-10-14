@props(['name', 'label' => 'Ikon', 'value' => null, 'compact' => false])

@php
    $current = old($name, $value) ?: 'fa-star';
    $icons = [
        'fa-star', 'fa-trophy', 'fa-medal', 'fa-award', 'fa-crown', 'fa-book-quran', 'fa-book-open', 'fa-book-open-reader',
        'fa-mosque', 'fa-hands-praying', 'fa-person-praying', 'fa-scroll', 'fa-pen-nib', 'fa-spell-check', 'fa-language', 'fa-brain',
        'fa-scale-balanced', 'fa-square-root-variable', 'fa-flask', 'fa-microscope', 'fa-earth-asia', 'fa-laptop-code', 'fa-robot', 'fa-calculator',
        'fa-campground', 'fa-flag', 'fa-kit-medical', 'fa-people-group', 'fa-users', 'fa-user-tie', 'fa-user-check', 'fa-handshake',
        'fa-futbol', 'fa-basketball', 'fa-volleyball', 'fa-feather', 'fa-hand-fist', 'fa-person-running', 'fa-person-swimming', 'fa-chess',
        'fa-music', 'fa-drum', 'fa-drum-steelpan', 'fa-guitar', 'fa-microphone', 'fa-palette', 'fa-camera', 'fa-film',
        'fa-coins', 'fa-hand-holding-heart', 'fa-box-open', 'fa-heart', 'fa-calendar-days', 'fa-clock', 'fa-clipboard-check', 'fa-list-check',
        'fa-file-invoice', 'fa-file-circle-check', 'fa-cash-register', 'fa-shirt', 'fa-school', 'fa-building', 'fa-briefcase', 'fa-circle-info',
    ];
@endphp
<div {{ $attributes->only('class')->merge(['class' => 'relative']) }} data-icon-picker>
    @unless ($compact)
        <span class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }}</span>
    @endunless
    <div class="flex gap-2">
        <span data-icon-preview class="w-11 h-11 shrink-0 rounded-xl bg-tosca-50 border border-tosca-100 text-tosca-700 flex items-center justify-center text-lg"><i class="fa-solid {{ $current }}"></i></span>
        <input type="text" name="{{ $name }}" value="{{ $current }}" data-icon-input readonly
            class="flex-1 min-w-0 px-3 py-2.5 rounded-xl border border-slate-200 text-sm bg-white text-slate-600 cursor-pointer focus:ring-2 focus:ring-tosca-600 focus:outline-none" title="Klik untuk memilih ikon">
        <button type="button" data-icon-toggle class="shrink-0 px-3 rounded-xl border border-slate-200 text-slate-600 hover:border-tosca-600 hover:text-tosca-700 text-sm font-semibold transition"><i class="fa-solid fa-icons"></i></button>
    </div>
    <div data-icon-grid class="hidden absolute z-30 mt-2 left-0 right-0 sm:right-auto sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 p-3">
        <p class="text-xs font-semibold text-slate-500 mb-2 px-1">Pilih ikon:</p>
        <div class="grid grid-cols-8 gap-1 max-h-60 overflow-y-auto">
            @foreach ($icons as $icon)
                <button type="button" data-icon="{{ $icon }}" title="{{ $icon }}" class="aspect-square rounded-lg flex items-center justify-center text-slate-600 hover:bg-tosca-50 hover:text-tosca-700 transition {{ $icon === $current ? 'bg-tosca-700 text-white hover:bg-tosca-700 hover:text-white' : '' }}"><i class="fa-solid {{ $icon }}"></i></button>
            @endforeach
        </div>
    </div>
    @error($name)
        <p class="text-xs font-semibold text-rose-600 mt-1.5"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
    @enderror
</div>
