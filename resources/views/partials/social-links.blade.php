@foreach (['facebook' => 'fa-facebook-f', 'instagram' => 'fa-instagram', 'youtube' => 'fa-youtube', 'tiktok' => 'fa-tiktok'] as $network => $icon)
    @if (setting('social_'.$network))
        <a href="{{ setting('social_'.$network) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($network) }}" class="hover:text-gold-400 transition"><i class="fa-brands {{ $icon }}"></i></a>
    @endif
@endforeach
