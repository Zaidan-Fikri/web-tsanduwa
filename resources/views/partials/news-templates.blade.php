{{-- Isi modal berita. Semua teks di-escape oleh Blade, jadi aman dimasukkan ke DOM. --}}
@foreach ($posts as $newsItem)
    <template id="news-tpl-{{ $newsItem->id }}">
        <span class="text-xs text-tosca-700 font-bold">{{ $newsItem->dateLabel() }}</span>
        <h3 class="text-2xl font-bold text-slate-900 mt-1 mb-4 pr-6">{{ $newsItem->title }}</h3>
        <div class="prose text-slate-700 text-sm leading-relaxed space-y-3">{{ paragraphs($newsItem->content) }}</div>
        <div class="mt-6 pt-4 border-t border-slate-100 text-right">
            <a href="{{ route('berita.show', $newsItem) }}" class="text-tosca-700 font-bold text-sm hover:underline">Buka halaman berita &rarr;</a>
        </div>
    </template>
@endforeach
