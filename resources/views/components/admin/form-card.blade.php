@props(['action', 'method' => 'POST', 'cancel' => null, 'submit' => 'Simpan', 'files' => false])

<form action="{{ $action }}" method="POST" @if ($files) enctype="multipart/form-data" @endif class="bg-white rounded-2xl shadow-sm border border-slate-100" data-dirty-check>
    @csrf
    @unless (strtoupper($method) === 'POST')
        @method($method)
    @endunless

    @if ($errors->any())
        <div class="mx-6 mt-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
            <p class="font-bold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Ada isian yang perlu diperbaiki:</p>
            <ul class="list-disc ml-6 mt-1 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-6 space-y-5">
        {{ $slot }}
    </div>

    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-2">
        @if ($cancel)
            <a href="{{ $cancel }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition text-center">Batal</a>
        @endif
        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg transition inline-flex items-center justify-center gap-2 hover:-translate-y-0.5">
            <i class="fa-solid fa-floppy-disk text-gold-400"></i> {{ $submit }}
        </button>
    </div>
</form>
