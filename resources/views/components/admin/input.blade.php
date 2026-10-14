@props(['name', 'label', 'value' => null, 'type' => 'text', 'help' => null, 'required' => false, 'placeholder' => null])

@php($id = 'f_'.str_replace(['[', ']', '.'], '_', $name))
<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    <label for="{{ $id }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }} @if ($required)<span class="text-rose-500">*</span>@endif</label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" @required($required)
        {{ $attributes->except('class') }}
        class="w-full px-4 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-tosca-600 focus:border-tosca-600 focus:outline-none transition {{ $errors->has($name) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-200 bg-white' }}">
    @if ($help)
        <p class="text-xs text-slate-500 mt-1.5">{{ $help }}</p>
    @endif
    @error($name)
        <p class="text-xs font-semibold text-rose-600 mt-1.5"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
    @enderror
</div>
