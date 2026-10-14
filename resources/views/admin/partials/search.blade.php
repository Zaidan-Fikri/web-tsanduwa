<form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2">
    <div class="relative flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder ?? 'Cari...' }}" maxlength="100"
            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
    </div>
    @isset($filterName)
        <select name="{{ $filterName }}" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
            <option value="">{{ $filterAll ?? 'Semua' }}</option>
            @foreach ($filterOptions as $value => $label)
                <option value="{{ $value }}" @selected(request($filterName) === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    @endisset
    <button type="submit" class="px-5 py-2.5 rounded-xl bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-bold transition shadow-md hover:shadow-lg hover:-translate-y-0.5">Cari</button>
    @if (request('q') || (isset($filterName) && request($filterName)))
        <a href="{{ url()->current() }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 text-center transition">Reset</a>
    @endif
</form>
