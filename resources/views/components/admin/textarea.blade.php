@props(['name', 'label', 'value' => null, 'rows' => 4, 'help' => null, 'required' => false, 'placeholder' => null])

@php($id = 'f_'.str_replace(['[', ']', '.'], '_', $name))
<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }} @if ($required)<span class="text-rose-500">*</span>@endif</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" @required($required)
        {{ $attributes->except('class') }}
        class="w-full px-4 py-2.5 rounded-xl border text-sm leading-relaxed focus:ring-2 focus:ring-tosca-600 focus:border-tosca-600 focus:outline-none transition {{ $errors->has($name) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-200 bg-white' }}">{{ old($name, $value) }}</textarea>
    @if ($help)
        <p class="text-xs text-slate-500 mt-1.5">{{ $help }}</p>
    @endif
    @error($name)
        <p class="text-xs font-semibold text-rose-600 mt-1.5"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
    @enderror
</div>
