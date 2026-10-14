@props(['src' => null, 'icon' => 'fa-image', 'size' => 'w-16 h-12'])

<span class="{{ $size }} shrink-0 rounded-lg overflow-hidden bg-gradient-to-br from-tosca-500 to-tosca-800 text-gold-400 flex items-center justify-center">
    @if ($src)
        <img src="{{ media_url($src) }}" alt="" loading="lazy" class="w-full h-full object-cover">
    @else
        <i class="fa-solid {{ $icon }}"></i>
    @endif
</span>
